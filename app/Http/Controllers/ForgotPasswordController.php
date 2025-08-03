<?php

namespace App\Http\Controllers;

use App\Mail\ForgetPassword;
use Illuminate\Http\Request;
use App\Rules\EmailValidation;
use App\Rules\PasswordValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Rules\TurnstileValidation;

class ForgotPasswordController extends Controller
{
    public function showResetPasswordForm($token) {
        return view('oauth.reset_password', ['token' => $token]);
    }

    public function showForgetPasswordForm() {
        return view('oauth.forget_password');
    }


    public function submitResetPasswordForm(Request $request) {
        $request->validate([
            'email' => ['required', new EmailValidation, 'exists:users'],
            'password' => ['required', new PasswordValidation , 'confirmed'],
            'cf-turnstile-response' => ['required', new TurnstileValidation($request)],
        ]);

        DB::beginTransaction();
        try {
            $updatePassword = DB::table('password_resets')
                ->where([
                    'email' => $request->email,
                    'token' => $request->token,
                ])
                ->first();

            if (!$updatePassword) {
                return redirect()->back()->withErrors(['email' => 'Invalid token!']);
            }

            $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email' => $request->email])->delete();

            DB::commit();

        } catch (\Exception $e) {
            $this->logToSentryWithTimeout($e);
            return redirect()->back()->withErrors(['error' => 'An error occurred while resetting the password.']);
        }

        return redirect()->route('login.view')->with('success_messages', ['Password has been reset successfully. Please start login process again.']);
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => ['required', new EmailValidation, 'exists:users'],
            'cf-turnstile-response' => ['required', new TurnstileValidation($request)],
        ]);

        $token = Str::random(64);

        try {

            $existingToken = DB::table('password_resets')->where('email', $request->email)->first();

            if ($existingToken) {
                return redirect()->back()->withErrors(['email' => 'A password reset link has already been sent to this email. Please check your inbox.']);
            }

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::to($request->email)->send(new ForgetPassword($token));

        }catch (\Exception $e) {
            $this->logToSentryWithTimeout($e);
            return redirect()->back()->withErrors(['email' => 'An error occurred while sending the password reset link. Please try again later.']);
        }

        return redirect()->route('login.view')->with('success_messages', ['We have sent a password reset link to your email.']);
    }  

}
