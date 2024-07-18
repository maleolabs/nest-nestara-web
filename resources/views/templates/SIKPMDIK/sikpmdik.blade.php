@extends('templates.layouts.template-app')
@push('margin-paper')px-3 @endpush
@section('content')
    <p class="fs-5 text-center text-decoration-underline mb-0 pt-3">
        SURAT IZIN KERJA PEREKAM MEDIS DAN INFORMASI KESEHATAN
    </p>
    <p class="fw-bold text-center normal-spacing"> Nomor : 503/DPMPTSP-BB/IPM/0225/VI/2024</p>

    <img src="data:image;base64,{{ base64_encode(file_get_contents(public_path('storage/pdf-assets/free.png'))) }}" alt="free" class="free-watermark-right straight-horizontal top-n25 scale-n1">

    <p class="fs-6 text-justify">
        Berdasarkan Undang-undang No.36 Tahun 2014 Tentang Tenaga Kesehatan, Peraturan Menteri Kesehatan RI Nomor 55 Tahun 2013 tentang Penyelenggaraan Pekerjaan Perekam Medis danPeraturan Bupati Bone Bolango No 6 Tahun 2022 Tentang Pendelegasian Wewenang Penerbitan dan Penandatanganan Perizinan dan Non Dinas Penanaman Modal, Pelayanan Terpadu Satu Pintu Kabupaten Bone Bolango memberikan Izin Kerja Perekam Medis dan Informasi Kesehatan kepada :
    </p>

    <p class="text-center text-decoration-underline text-uppercase fs-5">
        Ma'rij Mokoginta
    </p>

    <table>
        <tr>
            <td class="fs-6" style="width: 25%">
                Tempat / Tgl. Lahir
            </td>
            <td class="fs-6" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    TELAGA KAB. GORONTALO, 21 MEI 1999
                </p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 25%">
                Alamat
            </td>
            <td class="fs-6" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    DUSUN I KEL./DESA TULADENGGI KECAMATAN TELAGA BIRU KOTA/KAB. GORONTALO PROPINSI GORONTALO
                </p>
            </td>
        </tr>
        <tr>
            <td class="fs-6 pt-2" style="width: 25%">
                Nomor STR
            </td>
            <td class="fs-6 pt-2" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    CS00000906847411
                </p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 25%">
                Nomor Surat Rekomendasi
            </td>
            <td class="fs-6" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    -
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="py-4">
                Diberikan izin kerja untuk melakukan pekerjaan Perekam Medis dan Informasi Kesehatan pada:
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 25%">
                Tempat Praktik
            </td>
            <td class="fs-6" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    PUSKESMAS SUWAWA TENGAH
                </p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 25%">
                Alamat
            </td>
            <td class="fs-6" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    JL. LINGKAR KECAMATAN DESA DUANO KEC. SUWAWA TENGAH KAB. BONE BOLANGO
                </p>
            </td>
        </tr>
        <tr>
            <td class="fs-6 pb-5" style="width: 25%">
                Berlaku Sampai
            </td>
            <td class="fs-6 pb-5" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    10 JULI 2029
                </p>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width: 50%" class="pt-5">
                <img src="data:image;base64,{{ base64_encode(file_get_contents(public_path('storage/pdf-assets/dummy-qrcode.png'))) }}" class="mt-3">
                <p class="fs-6 mb-0 mt-5">
                    Tembusan Yth.
                </p>
                <ol class="ps-3 mb-0">
                    <li class="fs-6">
                        Kepala Dinas Kesehatan Kabupaten Bone Bolango.
                    </li>
                    <li class="fs-6">
                        Kepala Dinas Kesehatan Kabupaten Bone Bolango.
                    </li>
                    <li class="fs-6">
                        Pertinggal
                    </li>
                </ol>
            </td>
            <td style="width: 50%" class="pt-5 text-center">
                <p class="fs-55 mb-0 normal-spacing">Ditetapkan di Tilongkabila</p>
                <p class="fs-55 mb-1 normal-spacing">Pada tanggal 10 Juli 2024</p>
                <p class="fs-55 normal-spacing">KEPALA DINAS</p>
                <p class="fs-55 mt-5 pt-2 normal-spacing text-decoration-underline mb-0">JUMAIDIL, AP, S.Sos,M.Ec.Dev</p>
                <p class="fs-55 mb-0 normal-spacing">Pembina Utama Muda</p>
                <p class="fs-55 normal-spacing">Nip. 19741018 199311 1 002</p>
            </td>
        </tr>
    </table>
@endsection