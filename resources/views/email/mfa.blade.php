<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .email-container {
            background-color: #ffffff;
            color: #2E2D2D;
            border-radius: 12px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }

        .email-container h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #FED166;
        }

        .email-container p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .verification-code {
            display: inline-block;
            background-color: #FED166;
            color: #000;
            font-size: 24px;
            font-weight: bold;
            padding: 12px 24px;
            border-radius: 8px;
            margin: 25px 0;
            letter-spacing: 2px;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #2E2D2D;
        }

        @media (max-width: 576px) {
            .email-container {
                padding: 25px;
            }

            .verification-code {
                font-size: 20px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <p>Hi,</p>
    <p>Someone tried to sign in with:</p>
    <p><strong>{{ $email }}</strong></p>

    <p>If it was you, enter this confirmation code in the app:</p>
    <div class="verification-code">{{ $code }}</div>

    <p>If you didn’t request this, you can safely ignore this email.</p>

    <div class="footer">
        <p>This code expires in 5 minutes.</p>
    </div>
</div>
</body>
</html>
