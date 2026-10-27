<!doctype html>
<html lang="id">
<body style="margin:0;background:#f4f7f6;color:#243332;font-family:Arial,sans-serif;line-height:1.6">
<div style="max-width:600px;margin:32px auto;background:#fff;border-top:5px solid #176b5b;padding:32px">
    <h1 style="margin-top:0;color:#176b5b;font-size:24px">Izin siap diserahkan</h1>
    <p>Yth. {{ $izin->pemohon?->user?->name ?: 'Pemohon' }},</p>
    <p>Permohonan izin Anda telah selesai diterbitkan dan siap diambil melalui layanan penyerahan.</p>
    <table style="width:100%;border-collapse:collapse;margin:24px 0">
        <tr><td style="padding:8px 0;color:#687876">Nomor pendaftaran</td><td style="padding:8px 0"><strong>{{ $izin->no_pendaftaran }}</strong></td></tr>
        <tr><td style="padding:8px 0;color:#687876">Jenis izin</td><td style="padding:8px 0"><strong>{{ $izin->jenis_perizinan?->nama ?: '-' }}</strong></td></tr>
        @if($izin->no_surat)<tr><td style="padding:8px 0;color:#687876">Nomor surat</td><td style="padding:8px 0"><strong>{{ $izin->no_surat }}</strong></td></tr>@endif
    </table>
    <p>Silakan mengikuti informasi jadwal dan lokasi penyerahan dari petugas layanan.</p>
    <p style="color:#687876;font-size:13px">Pesan ini dikirim otomatis. Jangan membalas email ini.</p>
</div>
</body>
</html>
