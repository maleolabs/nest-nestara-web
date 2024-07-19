@extends('templates.layouts.template-app')
@section('content')
    <p class="fs-5 text-center text-decoration-underline mb-0">SURAT KETERANGAN PENELITIAN</p>
    <p class="fw-bold text-center normal-spacing"> Nomor : 503/DPMPTSP-BB/IPM/0225/VI/2024</p>

    <img src="data:image;base64,{{ base64_encode(file_get_contents(public_path('storage/pdf-assets/free.png'))) }}" alt="free" class="free-watermark-right">

    <table>
        <tr>
            <td class="fs-6" style="width: 18%">Dasar</td>
            <td style="width: 82%">
                <span style="float: left">:</span>
                <ol class="ps-4 mb-0">
                    <li class="text-justify fs-7 semi-tight-spacing">
                        Peraturan Menteri Dalam Negeri Nomor 3 Tahun 2018 Tentang Surat Keterangan Penelitian
                    </li>
                    <li class="text-justify fs-7 semi-tight-spacing">
                        Peraturan Bupati Bone Bolango No 6 Tahun 2022 Tentang Pendelegasian Wewenang Penerbitan dan Penandatanganan Perizinan dan Non Perizinan Kepada Peyelenggara Pelayanan Terpadu Satu Pintu.
                    </li>
                </ol>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">Membaca</td>
            <td class="fs-6" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0">Dokumen Protokol/Proposal Penelitian yang diajukan secara lengkap.</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">Menimbang</td>
            <td class="fs-6" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">SURAT WAKIL DEKAN BIDANG AKADEMIK FAKULTAS TEKNIK UNIVERSITAS NEGERI GORONTALO NOMOR : B/1002/UN47.B5/PT.01.04/2024 TERTANGGAL 6 JUNI 2024 PERIHAL REKOMENDASI PENELITIAN</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p class="fs-5 text-center mb-0">MENERANGKAN</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">a. Nama</td>
            <td class="fs-6 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0">Ma'rij Mokoginta</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">b. Alamat Peneliti</td>
            <td class="text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 fs-8 tight-spacing">Jalan Rusli Datau II, Kelurahan Dulomo Utara, Kecamatan Kota Utara, Kota Gorontalo</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6 pt-2" style="width: 18%">c. Judul Penelitian</td>
            <td class="fs-8 tight-spacing pt-2 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">SISTEM INFORMASI PERIZINAN DINAS PMPTSP</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6 pt-5" style="width: 18%">d. Tujuan Penelitian</td>
            <td class="fs-8 pt-5 tight-spacing pt-4 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">Penyusunan Skripsi</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">e. Tempat Penelitian</td>
            <td class="fs-8 tight-spacing text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">Dinas Penanaman Modal Pelayanan Terpadu Satu Pintu</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6 pt-5" style="width: 18%">f. Waktu Penelitian</td>
            <td class="fs-6 pt-5 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">Sejak Ditetapkan</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">g. Bidang Penelitian</td>
            <td class="fs-6 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">Sistem Informasi</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">h. Status Penelitian</td>
            <td class="fs-6 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">Baru</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">i. Penanggungjawab</td>
            <td class="fs-6" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">Ir. Joko Widodo</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">k. Nama Lembaga</td>
            <td class="fs-6 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">Universitas Negeri Gorontalo</p>
            </td>
        </tr>
    </table>

    <p class="fs-7 mt-3 mb-2">
        Pada prinsipnya rencana penelitian yang bersangkutan tidak bertentangan dengan Pancasila dan Undang-undang Dasar 1945 serta yang bersangkutan disetujui untuk melanjutkan penelitian dengan ketentuan :
    </p>
    <ol class="ps-3">
        <li class="text-justify fs-8">
            Ruang Lingkup dan Lokasi Penelitian yang bersangkutan bedasarkan pada kerangka kerja/proposal/protokol yang disampaikan.
        </li>
        <li class="text-justify fs-8">
            Peneliti harus menyampaikan hasil penelitiannya kepada Pemerintah Daerah Kabupaten Bone Bolango melalui Dinas Penanaman Modal Pelayanan Terpadu Satu Pintu setelah selesai melakukan penelitian.
        </li>
        <li class="text-justify fs-8">
            Surat Keterangan Penelitian ini berlaku sampai dengan 6 JUNI 2025 dan selanjutnya dapat diperpanjang kembali apabila yang bersangkutan belum selesai melakukan penelitian
        </li>
    </ol>
    <p class="mt-3 fs-6 normal-spacing">
        Demikian Surat Keterangan Penelitian ini diberikan untuk dipergunakan sebagaimana mestinya dalam melakukan penelitian
    </p>

    <table>
        <tr>
            <td style="width: 50%">
                <img src="{{ $qrcode }}">
                <p class="fs-85 mb-0 mt-3">
                    Tembusan Yth.
                </p>
                <ol class="ps-3 mb-0">
                    <li class="fs-85">
                        Bupati Bone Bolango di Suwawa sebagai laporan.
                    </li>
                    <li class="fs-85">
                        Universitas/Sekolah/Lembaga yang bersangkutan.
                    </li>
                </ol>
            </td>
            <td style="width: 50%" class="text-center">
                <p class="fs-55 mb-1 normal-spacing">Suwawa, 6 JUNI 2024</p>
                <p class="fs-55 normal-spacing">KEPALA DINAS</p>
                <p class="fs-55 mt-5 pt-2 normal-spacing text-decoration-underline mb-0">JUMAIDIL, AP, S.Sos,M.Ec.Dev</p>
                <p class="fs-55 mb-0 normal-spacing">Pembina Utama Muda</p>
                <p class="fs-55 normal-spacing">Nip. 19741018 199311 1 002</p>
            </td>
        </tr>
    </table>
@endsection