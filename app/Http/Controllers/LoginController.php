<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\Mail\MfaCode;
use App\Models\User;
use App\Notifications\ErrorSlackNotification;
use App\Rules\EmailValidation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        return view('oauth.login');
    }

    public function showCodeForm()
    {
        return view('oauth.mfa');
    }

    private function validateOAuthParams() {
        $oauthParams = session('oauth_params');
        $msg = 'Invalid client. Please start over.';

        if (!$oauthParams) {
            return $msg;
        }

        $clientId = $oauthParams['client_id'] ?? null;
        $redirectUri = $oauthParams['redirect_uri'] ?? null;
        $codeChalenge = $oauthParams['code_challenge'] ?? null;
        $codeChallengeMethod = $oauthParams['code_challenge_method'] ?? null;
        $responseType = $oauthParams['response_type'] ?? null;
        $state = $oauthParams['state'] ?? null;

        if (!$clientId || !$redirectUri || !$codeChalenge || !$codeChallengeMethod || !$responseType || !$state) {
            return $msg;
        }

        if (!filter_var($redirectUri, FILTER_VALIDATE_URL)) {
            return $msg;
        }

        $client = Client::find($clientId);

        if (!$client) {
            return $msg;
        }

        if (!in_array($responseType, ['code', 'token'])) {
            return $msg;
        }

        if (!in_array($redirectUri, explode(',', $client->redirect))) {
            return $msg;
        }

        return true;
    }

    public function login(Request $request)
    {        
        $oauthValidation = $this->validateOAuthParams($request);
        if ($oauthValidation !== true) {
            return back()->withErrors(['email' => $oauthValidation]);
        }

        session()->forget('oauth_params');

        $credentials = $request->validate([
            'email' => ['required', new EmailValidation],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Credentials do not match.']);
        }

        if (!$user->has_email_verified) {
            return $this->handleUnverifiedUser($user);
        }

        $this->sendMfaCode($user);

        session(['mfa_user_id' => $user->id]);

        return redirect()->route('code.view')->with('success_messages', ['MFA code sent to your email.']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'numeric'],
        ]);

        $userId = session('mfa_user_id');
        if (!$userId) {
            return back()->withErrors(['code' => 'Oops, an error occurred!']);
        }

        $cachedCode = decrypt(Cache::get("mfa_code_{$userId}"));

        if (!$cachedCode) {
            return back()->withErrors(['code' => 'No valid code found or it expired.']);
        }

        if ((int)$request->code !== (int)$cachedCode) {
            return back()->withErrors(['code' => 'Invalid MFA code.']);
        }

        cache()->forget("mfa_code_{$userId}");

        $user = User::findOrFail($userId);
        Auth::login($user);
        $request->session()->regenerate();
        session()->forget('mfa_user_id');

        return redirect()->intended('/oauth/authorize');
    }

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();

        $accessToken->revoke();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        return response()->json([
            'success' => true,
            'message' => "Logged out successfully",
            'data' => null
        ]);
    }


    private function handleUnverifiedUser(User $user)
    {
        $msg = 'Your account has not been verified.';

        $lastLinkSent = $user->verification_link_sent_at
            ? Carbon::parse($user->verification_link_sent_at)->addMinutes(60)
            : null;

        if ($lastLinkSent && now()->lessThan($lastLinkSent)) {
            $msg .= ' You have a pending verification email, check your inbox.';
        } else {
            try {
                $url = URL::temporarySignedRoute(
                    'email.verification',
                    now()->addMinutes(60),
                    ['id' =>  $user->id]
                );

                Mail::to($user->email)->send(new EmailVerification($url));
                $user->verification_link_sent_at = now();
                $user->save();

                $msg .= ' A new verification email has been sent.';
            } catch (\Exception $e) {
                $notification = new ErrorSlackNotification($e);
                Notification::route('slack', env('SLACK_WEBHOOK'))->notify($notification);
                return back()->withErrors(['email' => 'Could not send a new verification email, try again later.']);
            }
        }

        return back()->withErrors(['email' => $msg]);
    }

    private function sendMfaCode(User $user): void
    {
        DB::beginTransaction();

        try {

            $code = rand(100000, 999999);
            
            Cache::put("mfa_code_{$user->id}", encrypt($code), now()->addMinutes(5));

            Mail::to($user->email)->send(new MfaCode($code, $user->email));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $notification = new ErrorSlackNotification($e);
            Notification::route('slack', env('SLACK_WEBHOOK'))->notify($notification);
            throw $e;
        }
    }
}
