<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lockity - Mfa Code</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />

    <style>
        .nano_body {
            font-size: 16px;
            display: block;
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }

        .nano_wrapper {
            font-size: 16px;
            box-sizing: border-box;
            overflow: hidden;
            width: 100%;
            display: block;
            font-weight: normal;
            font-family: 'Poppins', 'Google Sans', 'Open Sans', 'Roboto', 'Segoe UI', 'Helvetica Neue', Helvetica, Tahoma, Arial, monospace, sans-serif;
            background: #fafafa;
            color: #707070;
        }

        .nano_table {
            font-size: 16px;
            border-collapse: collapse;
            border-spacing: 0;
            border: 0px;
            width: 640px;
            max-width: 90%;
            margin: 50px auto;
            border-radius: 5px;
            overflow: hidden;
        }

        .nano_table tr {
            background: transparent;
            box-sizing: border-box;
            color: #222;
            background: #ffffff;
        }

        .nano_table td,
        .nano_table th {
            border: 0px;
            border-spacing: 0;
            border-collapse: collapse;
            box-sizing: border-box;
        }

        .nano_table tr td {
            box-sizing: border-box;
        }

        .nano_table a {
            color: #0075ff;
            font-weight: bold;
            overflow: hidden;
            box-sizing: border-box;
        }

        .nano_table a:hover {
            color: #00b13f;
        }

        .nano_table a:active {
            color: #ff6600;
        }

        .nano_table a:visited {
            color: #8e8e8e;
        }

        .nano_table a.nano_link {
            display: inline-block;
            width: auto !important;
            outline: none !important;
            text-decoration: none !important;
        }

        .nano_table a.nano_round_link {
            float: right;
            margin-top: 90px;
            font-weight: 400;
            width: auto !important;
            outline: none !important;
            text-decoration: none !important;
            padding: 15px;
            color: #0075ff;
            border: 1px solid #0075ff;
            border-radius: 50px;
        }

        .nano_table a.nano_arrow_link {
            display: inline-block;
            width: 100% !important;
            outline: none !important;
            color: #0075ff;
        }

        .nano_table a.nano_arrow_link img {
            float: left;
            width: 18px;
            margin-right: 10px;
            margin-bottom: 0px;
        }

        .nano_table img,
        .nano_table a img {
            box-sizing: border-box;
            display: block;
            height: auto;
            max-width: 100%;
            margin-bottom: 20px;
            border-radius: 10px;
            border: 0px;
            overflow: hidden;
        }

        .nano_table a.nano_button {
            display: inline-block;
            font-weight: 500;
            font-size: 16px;
            margin: 20px 0px;
            padding: 15px 50px;
            box-sizing: border-box;
            color: black !important;
            background: #FED166 !important;
            border-radius: 6px;
            text-decoration: none;
            outline: none;
        }


        /* Overvrite style */

        .nano_margin {
            float: left;
            width: 100%;
            overflow: hidden;
            height: 40px;
            padding-bottom: 0px;
            box-sizing: border-box;
        }

        .nano_company img {
            width: 60px !important;
            height: auto !important;
            margin-bottom: 10px !important;
            border-radius: 4px !important;
        }

        .nano_div {
            float: left;
            width: 100%;
            overflow: hidden;
            box-sizing: border-box;
        }

        .nano_bg {
            font-size: 14px;
            color: #3c4043;
            background: #fafafa;
        }

        .nano_radius {
            border-radius: 6px;
        }

        .nano_border {
            border: 1px solid #f0f0f0
        }

        .nano_bg a {
            box-sizing: border-box;
            color: #3c4043;
            font-weight: normal;
            overflow: hidden;
        }

        .nano_h1,
        .nano_h2,
        .nano_h3,
        .nano_h4,
        .nano_h7 {
            font-weight: 600;
            float: left;
            width: 100%;
            margin: 0px 0px 20px 0px !important;
            padding: 0px;
            box-sizing: border-box;
            color: #3c4043;
        }


        .nano_h1 {
            font-size: 29px;
        }

        .nano_h2 {
            font-size: 26px;
        }

        .nano_h3 {
            font-size: 23px;
        }

        .nano_h4 {
            font-size: 20px;
        }

        .nano_h7 {
            font-size: 18px;
            font-weight: 400;
        }

        .nano_p {
            box-sizing: border-box;
            font-size: 16px;
            float: left;
            width: 100%;
            margin: 0px 0px 20px 0px !important;
            color: #5f6368;
        }

        .nano_code {
            float: left;
            width: 100%;
            overflow: hidden;
            margin: 20px 0px;
            padding: 15px;
            box-sizing: border-box;
            border-radius: 6px;
            border: 1px dashed #FED166 ;
            background: #ffe60025;
            color: black;
            font-weight: 700;
            font-size: 23px;
            text-align: center;
        }

        .nano_code ul {
            display: inline-block;
            margin: 5px 5px;
            padding: 10px 20px;
            box-sizing: border-box;
            min-width: 30px;
            border-radius: 6px;
            border: 1px dashed #FED166 ;
            background: #fff;
            color: black;
            font-weight: 700;
            font-size: 21px;
        }

        .nano_divider {
            float: left;
            width: 100%;
            overflow: hidden;
            margin: 20px 0px;
            border-top: 1px solid #f0f0f0;
        }

        .nano_flex {
            float: left;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .nano_flex a.nano_icon {
            display: inline-block;
        }

        .nano_flex a.nano_icon img {
            display: block;
            width: 30px;
            margin: 5px;
        }


        .nano_rights {
            color: #706d6b;
        }

        .nano_rights-font {
            font-size: 13px;
        }

        .nano_half-flex {
            float: left;
            width: 100%;
        }

        .nano_half {
            float: left;
            width: 50%;
            height: 100%;
            box-sizing: border-box;
        }

        .nano_60 {
            float: left;
            width: 60%;
            height: auto;
            box-sizing: border-box;
        }

        .nano_40 {
            float: left;
            width: 40%;
            height: auto;
            box-sizing: border-box;
        }

        .nano_padding {
            float: left;
            width: 100%;
            height: auto;
            padding: 25px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .nano_regards {
            float: left;
            width: 100%;
            height: auto;
        }

        .nano_regards-text {
            font-size: 18px;
            font-weight: 700;
        }

        @media screen and (max-width: 640px) {
            .nano_half {
                width: 100%;
            }

            .nano_60 {
                width: 100%;
            }

            .nano_40 {
                width: 100%;
            }

            .nano_table a.nano_round_link {
                margin-top: 10px;
            }
        }
    </style>

</head>

<body class="nano_body">
    <div class="nano_wrapper">
        <table class="nano_table">
            <tbody>
                <tr>
                    <td>
                        <div class="nano_padding">
                            <div class="nano_h1">Multi-factor authentication code</div>
                            <div class="nano_p">Hi there! Someone tried to sign in to your Lockity account using the email address: <strong>{{ $email ?? 'your registered email' }}</strong>. If this was you, please enter the confirmation code below to complete your login securely.</div>

                            <div class="nano_code">
                                @for($i = 0; $i < 6; $i++)
                                    <ul>{{ $code[$i] ?? ($i + 1) }}</ul>
                                @endfor
                            </div>
                            <div class="nano_p">This code will expire in 5 minutes.</div>
                        </div>
                    </td>
                </tr>



                <tr>
                    <td>
                        <div class="nano_padding">
                            <div class="nano_regards">
                                <div class="nano_regards-text">Best regards</div>
                                <div class="nano_regards-name">The Lockity Team</div>
                            </div>

                            <div class="nano_divider"></div>

                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="nano_padding nano_bg nano_rights nano_rights-font">
                            <div class="nano_company">
                                <img src="https://guikspbicskovcmvfvwb.supabase.co/storage/v1/object/public/lockity-public-images/logos/lockity-logo-no-title.png" alt="Lockity Logo">

                                <div class="nano_address">
                                    <div class="nano_address-line">© {{date('Y')}} Lockity LLC</div>
                                </div>
                            </div>

                            <div class="nano_divider"></div>

                            <div class="nano_p nano_rights-font">If you didn’t request this, you can safely ignore this email.</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>