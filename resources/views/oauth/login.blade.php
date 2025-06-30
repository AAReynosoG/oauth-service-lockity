@extends('layouts.oauth_layout')
@section('title')
    Login - Lockity
@endsection

@section('tabs')
    <div class="nav-tabs-container">
        <a href="#" class="nav-tab route">Home</a>
        <a href="{{ route('register') }}" class="nav-tab route">Sign Up</a>
        <a href="#" class="nav-tab route active">Sign In</a>
    </div>
@endsection

@section('title-text')
    Welcome Back
@endsection

@section('form-content')
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required oninput="validateEmail()">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-container">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <span class="toggle-password" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <a href="#" style="cursor: pointer" class="form-label">Forgot Password</a>
                </div>
            </div>

            <button id="submit-button" type="submit" class="btn btn-login" disabled>Sign In</button>
        </form>
@endsection

<script>
    function validateEmail() {
        const email = document.getElementById('email').value;
        const submitButton = document.getElementById('submit-button')

        const emailRegex = /^(?=.{5,100}$)[\p{L}0-9._-]+@[\p{L}0-9-]+(?:\.[\p{L}0-9-]+)*\.[\p{L}0-9]{2,}$/u;

        const isFormValid = emailRegex.test(email)

        submitButton.disabled = !isFormValid;
    }

    setupPasswordToggle('password', 'togglePassword');
</script>
