@extends('layouts.oauth_layout')
@section('title')
    Code - Lockity
@endsection

@section('title-text')
    Enter MFA Code
@endsection

@section('form-content')
    <form id="form" method="POST" action="{{ route('code') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label for="code" class="form-label">Code</label>
                <input type="number" class="form-control" id="code" name="code" value="{{ old('code') }}" required oninput="validateCodeLength()">
            </div>
        </div>

        <div
            data-sitekey="0x4AAAAAABoCGDhVzX85jd4T"
            class="cf-turnstile"
        ></div>
        
        <button id="submit-button" type="submit" class="btn btn-login" disabled>Sign In</button>
    </form>
@endsection

<script>
    function validateCodeLength() {
        const submitButton = document.getElementById('submit-button');
        const input = document.getElementById('code');

        input.value = input.value.slice(0, 6);

        submitButton.disabled = input.value.length !== 6;
    }
</script>
