<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorización - Lockity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2E2D2D;
            color: #E1E1E1;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .auth-container {
            background-color: #3A3A3A;
            padding: 40px;
            border-radius: 12px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header img {
            max-height: 100px;
            margin-bottom: 10px;
        }

        .auth-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin: 0;
        }

        .auth-body {
            margin-bottom: 30px;
        }

        .auth-body p {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .scopes-list {
            background-color: #4A4A4A;
            border-left: 4px solid #FED167;
            padding: 15px 20px;
            border-radius: 6px;
            list-style: disc;
            color: #E1E1E1;
        }

        .scopes-list li {
            margin-bottom: 5px;
        }

        .auth-actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn-custom {
            flex: 1;
            padding: 12px;
            font-weight: 600;
            font-size: 16px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .btn-approve {
            background-color: #FED167;
            color: #2E2D2D;
            border: none;
        }

        .btn-approve:hover {
            background-color: #ffdd85;
        }

        .btn-deny {
            background-color: transparent;
            border: 2px solid #FED167;
            color: #FED167;
        }

        .btn-deny:hover {
            background-color: #FED167;
            color: #2E2D2D;
        }

        @media (max-width: 480px) {
            .auth-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-header">
        <img src="{{ asset('images/lockity-logo.png') }}" alt="Lockity Logo">
        <h1>Authorization Request</h1>
    </div>

    <div class="auth-body">
        <p><strong>{{ $client->name }}</strong> is requesting permission to access your account.</p>

        @if (count($scopes) > 0)
            <p><strong>This application will be able to:</strong></p>
            <ul class="scopes-list">
                @foreach ($scopes as $scope)
                    <li>{{ $scope->description }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="auth-actions">
        <form method="POST" action="{{ route('passport.authorizations.approve') }}">
            @csrf
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="btn btn-custom btn-approve">Authorize</button>
        </form>

        <form method="POST" action="{{ route('passport.authorizations.deny') }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="btn btn-custom btn-deny">Cancel</button>
        </form>
    </div>
</div>

</body>
</html>
