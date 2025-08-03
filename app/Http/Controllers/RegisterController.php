<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\Models\User;
use App\Rules\EmailValidation;
use App\Rules\FullNameValidation;
use App\Rules\PasswordValidation;
use App\Rules\TurnstileValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('oauth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', new FullNameValidation],
            'last_name' => ['required', new FullNameValidation],
            'second_last_name' => ['required', new FullNameValidation],
            'email' => ['required', new EmailValidation, 'unique:users,email'],
            'password' => ['required', new PasswordValidation , 'confirmed'],
            'cf-turnstile-response' => ['required', new TurnstileValidation($request)],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'second_last_name' => $request->second_last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $url = URL::temporarySignedRoute(
                'email.verification',
                now()->addMinutes(60),
                ['id' =>  $user->id]
            );

            Mail::to($user->email)->send(new EmailVerification($url));
            $user->verification_link_sent_at = now();
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logToSentryWithTimeout($e);
            return redirect()->back()->withErrors(['error' => 'Could not send verification email, your account could not be created. Try again later.']);
        }

        return redirect()->route('register.view')->with('success_messages', ['Registered Successfully. A verification email has been sent to your email address.']);
    }

    public function emailVerification(Request $request) {
        if (!$request->hasValidSignature()) {
            return redirect()->route('register.view')->withErrors(['error' => 'Invalid verification link.']);
        }

        $user = User::find($request->id);

        if(!$user) {
            return redirect()->route('register.view')->withErrors(['error' => 'User not found.']);
        }

        if ($user->has_email_verified) {
            return redirect()->route('register.view')->withErrors(['error' => 'Invalid verification link.']);
        }

        $user->has_email_verified = true;
        $user->save();

        return redirect()->route('login.view')->with('success_messages', ['Your email has been successfully verified!']);
    }
}
