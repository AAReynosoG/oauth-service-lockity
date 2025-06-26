<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code - Lockity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #2E2D2D;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            background-color: #2E2D2D;
            border-radius: 10px;
            overflow: hidden;
        }

        .logo-section {
            display: flex;
            justify-content: center;
            padding: 20px 0;
        }

        .logo-section img {
            max-height: 150px;
            width: auto;
        }

        .form-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 20px;
        }

        .welcome-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-text {
            font-size: 40px;
            font-weight: 600;
            color: #E1E1E1;
            margin: 0;
        }

        .form-content {
            width: 100%;
            max-width: 600px;
        }

        .form-label {
            color: #929496;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background-color: #515355;
            border: 1px solid #B1A8A8;
            color: white;
            height: 50px;
            padding: 10px 15px;
            border-radius: 0;
            margin-bottom: 20px;
            width: 100%;
        }

        .form-control:focus {
            background-color: #515355;
            border-color: #FED167;
            color: white;
            box-shadow: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .btn-login {
            background-color: #FED166;
            color: #2f2c2d;
            border: none;
            height: 50px;
            font-weight: 600;
            border-radius: 0;
            transition: all 0.3s;
            width: 100%;
            margin-top: 10px;
            font-size: 16px;
        }

        .btn-login:disabled {
            background-color: #FED166;
            color: #2E2D2D;
            border: none;
            height: 50px;
            font-weight: 600;
            border-radius: 0;
            transition: all 0.3s;
            width: 100%;
            margin-top: 10px;
            font-size: 16px;
        }

        .btn-login:hover {
            background-color: #FED166;
        }

        .error-message {
            background: rgba(220, 53, 69, 0.2);
            border-left: 4px solid #dc3545;
            padding: 10px 15px;
            border-radius: 0 4px 4px 0;
            margin-bottom: 20px;
            color: #ff6b6b;
            width: 100%;
        }

        .error-message ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .success-message {
            background: rgba(30, 114, 14, 0.2);
            border-left: 4px solid #5cdc35;
            padding: 10px 15px;
            border-radius: 0 4px 4px 0;
            margin-bottom: 20px;
            color: #5cdc35;
            width: 100%;
        }

        .success-message ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
        }

        @media (max-width: 768px) {
            .welcome-text {
                font-size: 32px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .logo-section img {
                max-height: 80px;
            }
        }

        @media (max-width: 480px) {
            .welcome-text {
                font-size: 28px;
            }

            .form-control {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <!-- Logo Lockity -->
    <div class="logo-section">
        <img src="{{ asset('images/lockity-logo.png') }}" alt="Lockity logo">
    </div>

    <div class="form-section">
        <!-- Title -->
        <div class="welcome-container">
            <h1 class="welcome-text">Enter MFA Code</h1>
        </div>

        <!-- Error messages -->
        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success messages -->
        @if (session('success_messages'))
            <div class="success-message">
                <ul>
                    @foreach (session('success_messages') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <div class="form-content">
            <form method="POST" action="{{ route('code') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="code" class="form-label">Code</label>
                        <input type="number" class="form-control" id="code" name="code" value="{{ old('code') }}" required oninput="validateCodeLength()">
                    </div>
                </div>
                <button id="submit-button" type="submit" class="btn btn-login" disabled>Sign In</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function validateCodeLength() {
        const submitButton = document.getElementById('submit-button');
        const input = document.getElementById('code');

        input.value = input.value.slice(0, 6);

        if (input.value.length < 6) {
            submitButton.disabled = true;
        }
    }
</script>
</body>
</html>
