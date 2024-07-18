@extends('templates.layouts.template-app')
@push('margin-paper')px-3 @endpush
@section('content')
    <p class="fs-5 text-center text-decoration-underline mb-0 pt-4">
        SURAT IZIN PRAKTEK PERAWAT (SIPP)
    </p>
    <p class="fw-bold text-center normal-spacing"> Nomor : 503/DPMPTSP-BB/IPM/0225/VI/2024</p>

    <img src="data:image;base64,{{ base64_encode(file_get_contents(public_path('storage/pdf-assets/free.png'))) }}" alt="free" class="free-watermark-right">

    <p class="fs-6 text-justify">
        Berdasarkan Peraturan Menteri Kesehatan Nomor 17 Tahun 2013 tentang Perubahan atas Peraturan Menteri Kesehatan Nomor HK.02.02/MENKES/148/I/2010 tentang Izin dan Penyelenggaraan Praktik Perawat yang ditindaklajuti dengan Peraturan Bupati Bone Bolango No 6 Tahun 2022 Tentang Pendelegasian Wewenang Penerbitan dan Penandatanganan Perizinan dan Non Perizinan Kepada Peyelenggara Pelayanan Terpadu Satu Pintu, yang bertandatangan di bawah ini, Kepala Dinas Penanaman Modal, Pelayanan Terpadu Satu Pintu Kabupaten Bone Bolango memberikan Izin Praktik kepada :
    </p>

    <p class="text-center text-decoration-underline text-uppercase fs-5">
        Ma'rij Mokoginta
    </p>

    <table>
        <tr>
            <td class="fs-55" style="width: 25%">
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
            <td class="fs-55" style="width: 25%">
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
            <td class="fs-55 pt-2" style="width: 25%">
                Nomor Rekomendasi
            </td>
            <td class="fs-6 pt-2" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    -
                </p>
            </td>
        </tr>
        <tr>
            <td class="fs-55" style="width: 25%">
                Tanggal Rekomendasi
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
                Untuk menjalankan praktik sebagai Perawat di:
            </td>
        </tr>
        <tr>
            <td class="fs-55" style="width: 25%">
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
            <td class="fs-55" style="width: 25%">
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
            <td colspan="2" class="py-4">
                Surat Izin Praktik Perawat ( SIPP ) ini berlaku sampai dengan tanggal : 1 MEI 2029
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
                <p class="fs-55 mb-0 normal-spacing">Ditetapkan di Suwawa</p>
                <p class="fs-55 mb-1 normal-spacing">Pada tanggal 10 Juli 2024</p>
                <p class="fs-55 normal-spacing">KEPALA DINAS</p>
                <p class="fs-55 mt-5 pt-2 normal-spacing text-decoration-underline mb-0">JUMAIDIL, AP, S.Sos,M.Ec.Dev</p>
                <p class="fs-55 mb-0 normal-spacing">Pembina Utama Muda</p>
                <p class="fs-55 normal-spacing">Nip. 19741018 199311 1 002</p>
            </td>
        </tr>
    </table>
@endsection