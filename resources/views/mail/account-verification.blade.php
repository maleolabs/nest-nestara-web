<!-- resources/views/emails/verify_email.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header h1 {
            color: #333333;
            font-size: 24px;
            margin: 0;
        }
        .email-body {
            color: #555555;
            line-height: 1.6;
        }
        .email-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888888;
        }
        .btn-verify {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn-verify:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Verifikasi Akun Anda</h1>
        </div>

        <div class="email-body">
            <p>Halo <strong>{{ str_capitalize($name, true) }}</strong>,</p>
            <p>Terima kasih telah mendaftar di aplikasi kami. Untuk menyelesaikan proses pendaftaran, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="btn-verify">Verifikasi Email</a>
            </div>

            <p>Jika Anda tidak merasa mendaftar di aplikasi kami, Anda dapat mengabaikan email ini.</p>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>