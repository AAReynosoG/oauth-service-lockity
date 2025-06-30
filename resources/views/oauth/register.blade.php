@extends('layouts.oauth_layout')
@section('title')
    Register - Lockity
@endsection

@section('tabs')
    <div class="nav-tabs-container">
        <a href="#" class="nav-tab route">Home</a>
        <a href="#" class="nav-tab route active">Sign Up</a>
        <a href="{{ route('login') }}" class="nav-tab route">Sign In</a>
    </div>
@endsection

@section('title-text')
    Create An Account
@endsection

@section('form-content')
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required oninput="validateForm()">
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required oninput="validateForm()">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="second_last_name" class="form-label">Second Last Name</label>
                <input type="text" class="form-control" id="second_last_name" name="second_last_name" value="{{ old('second_last_name') }}" required oninput="validateForm()">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required oninput="validateForm()">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
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

        <button id="submit-button" type="submit" class="btn btn-login" disabled>Sign Up</button>
    </form>
@endsection

<script>
    function validateForm() {
        const name = document.getElementById('name').value;
        const last_name = document.getElementById('last_name').value;
        const second_last_name = document.getElementById('second_last_name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        const submitButton = document.getElementById('submit-button')

        const emailRegex = /^(?=.{5,100}$)[\p{L}0-9._-]+@[\p{L}0-9-]+(?:\.[\p{L}0-9-]+)*\.[\p{L}0-9]{2,}$/u;
        const fullNameRegex = /^[A-Za-záéíóúüñÁÉÍÓÚÜÑ ]{3,100}$/;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[\S]{8,255}$/;

        const isFormValid =
            emailRegex.test(email) &&
            fullNameRegex.test(name) &&
            fullNameRegex.test(last_name) &&
            fullNameRegex.test(second_last_name) &&
            passwordRegex.test(password) &&
            password === passwordConfirmation;

        submitButton.disabled = !isFormValid;
    }

    setupPasswordToggle('password', 'togglePassword');
    setupPasswordToggle('password_confirmation', 'toggleConfirmPassword');
</script>

