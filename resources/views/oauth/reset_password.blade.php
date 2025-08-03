@extends('layouts.oauth_layout')
@section('title')
    Password Recovery - Lockity
@endsection

@section('title-text')
    Recover Your Password
@endsection

@section('form-content')
        <form id="form" method="POST" action="{{ route('reset.password') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-row">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required oninput="validateForm()">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <div class="password-container">
                        <input type="password" class="form-control" id="password" name="password" required oninput="validateForm()">
                        <span class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required oninput="validateForm()">
                            <span class="toggle-password" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <p  class="form-label">Your password must contain: </p>
                    <ul class="form-label">
                        <li>Minimum 8 characters</li>
                        <li>Maximum 255 characters</li>
                        <li>At least 1 lowercase letter</li>
                    </ul>
                </div>

                <div class="form-group">
                    <ul class="form-label">
                        <li>At least 1 capital letter</li>
                        <li>At least 1 digit (0-9)</li>
                        <li>At least 1 special character</li>
                        <li>No white spaces</li>
                    </ul>
                </div>
            </div>

            <div
                data-sitekey="0x4AAAAAABoCGDhVzX85jd4T"
                class="cf-turnstile"
            ></div>

            <button id="submit-button" type="submit" class="btn btn-login" disabled>Send</button>
        </form>
@endsection

<script>
    function validateForm() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        const submitButton = document.getElementById('submit-button')

        const emailRegex = /^(?=.{5,100}$)[\p{L}0-9._-]+@[\p{L}0-9-]+(?:\.[\p{L}0-9-]+)*\.[\p{L}0-9]{2,}$/u;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[\S]{8,255}$/;

        const isFormValid =
            emailRegex.test(email) &&
            passwordRegex.test(password) &&
            password === passwordConfirmation;

        submitButton.disabled = !isFormValid;
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupPasswordToggle('password', 'togglePassword');
        setupPasswordToggle('password_confirmation', 'toggleConfirmPassword');
    });
</script>
