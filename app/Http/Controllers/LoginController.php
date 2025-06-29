<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\Mail\MfaCode;
use App\Models\TwoFactorCode;
use App\Models\User;
use App\Notifications\ErrorSlackNotification;
use App\Rules\EmailValidation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('oauth.login');
    }

    public function showCodeForm()
    {
        return view('oauth.mfa');
    }


    public function login(Request $request)
    {
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

        $validCode = TwoFactorCode::where('user_id', $userId)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$validCode) {
            return back()->withErrors(['code' => 'No valid code found or it expired.']);
        }

        try {
            if ((int)$request->code !== (int)Crypt::decryptString($validCode->code)) {
                return back()->withErrors(['code' => 'Invalid MFA code.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['code' => 'Invalid MFA code format.']);
        }

        $validCode->update(['used' => true]);

        $user = User::findOrFail($userId);
        Auth::login($user);
        $request->session()->regenerate();
        session()->forget('mfa_user_id');

        return redirect()->intended('/oauth/authorize');
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => "Logged out successfully",
            'data' => null
        ], 200);
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
            TwoFactorCode::where('user_id', $user->id)->update(['used' => true]);

            $code = rand(100000, 999999);

            TwoFactorCode::create([
                'user_id' => $user->id,
                'code' => Crypt::encryptString($code),
                'expires_at' => now()->addMinutes(5),
            ]);

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
