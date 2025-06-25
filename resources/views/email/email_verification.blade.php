<!DOCTYPE html>
<html lang="en">
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
            font-size: 26px;
            margin-bottom: 20px;
        }

        .email-container p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .verify-link {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 25px;
            background-color: #FED166;
            color: #000;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
        }

        .verify-url {
            font-size: 14px;
            word-break: break-all;
            margin-top: 10px;
            color: #2E2D2D;
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

            .verify-link {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <h1>Verify Your Email</h1>

    <p>Thank you for using Lockity.</p>
    <p>Please confirm your email address by clicking the link below:</p>

    <p class="verify-url">{{ $url }}</p>

    <div class="footer">
        <p>This link will expire in 60 minutes.</p>
        <p>If you didn’t request this, you can safely ignore this email.</p>
    </div>
</div>
</body>
</html>
