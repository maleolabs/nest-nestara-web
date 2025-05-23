<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Perbaikan Dokumen</title>
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
        .btn-action {
            display: inline-block;
            padding: 10px 20px;
            background-color: #00CDB8;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn-action:hover {
            background-color: #D3E4DD;
        }
        .revision-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .revision-details h3 {
            margin-top: 0;
            color: #333333;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Notifikasi Perbaikan Dokumen</h1>
        </div>

        <div class="email-body">
            <p>Halo <strong>{{ $user->pemohon->nama_pemohon }}</strong>,</p>
            <p>Terima kasih telah mengajukan permohonan izin di Dinas PMPTSP Kabupaten Bone Bolango. Setelah melakukan verifikasi berkas/dokumen, kami menemukan beberapa hal yang perlu diperbaiki sebelum permohonan Anda dapat diproses lebih lanjut.</p>

            <div class="revision-details">
                <h3>Detail Revisi:</h3>
                <p>{{ $revision ?? 
                    'Tidak ada catatan revisi spesifik. Silakan periksa kembali dokumen Anda dan pastikan semuanya lengkap dan sesuai.' }}
                </p>
            </div>

            <p>Silakan perbaiki dokumen Anda sesuai dengan catatan di atas dan unggah kembali melalui sistem kami. Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi kami.</p>

            <div style="text-align: center;">
                <a href="{{ $url }}" class="btn-action">Lihat Permohonan</a>
            </div>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') . ' ' . env('APP_NAME') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>