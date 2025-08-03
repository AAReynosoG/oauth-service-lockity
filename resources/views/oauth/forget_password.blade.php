@extends('layouts.oauth_layout')
@section('title')
    Forget Password - Lockity
@endsection

@section('title-text')
    Forgot Password?
@endsection

@section('form-content')
        <form id="form" method="POST" action="{{ route('forget.password') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required oninput="validateEmail()">
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
    function validateEmail() {
        const email = document.getElementById('email').value;
        const submitButton = document.getElementById('submit-button')

        const emailRegex = /^(?=.{5,100}$)[\p{L}0-9._-]+@[\p{L}0-9-]+(?:\.[\p{L}0-9-]+)*\.[\p{L}0-9]{2,}$/u;

        const isFormValid = emailRegex.test(email)

        submitButton.disabled = !isFormValid;
    }
</script>
