@extends('templates.layouts.template-app')
@section('content')
    <p class="fs-5 text-center text-decoration-underline mb-0">{{ $konten['judul'] ?? 'BELUM DI SET' }}</p>
    <p class="fw-bold text-center normal-spacing"> Nomor : {{ $izin->no_surat }}</p>

    <img src="data:image;base64,{{ base64_encode(file_get_contents(public_path('storage/pdf-assets/free.png'))) }}" alt="free" class="free-watermark-right">

    <table>
        <tr>
            <td class="fs-6" style="width: 18%">Dasar</td>
            <td style="width: 82%">
                <span style="float: left">:</span>
                @if (isset($konten['dasar']))
                    <ol class="ps-4 mb-0">
                        @if (is_array($konten['dasar']) && count($konten['dasar']) > 0)
                            @foreach ($konten['dasar'] as $dasar)
                                <li class="text-justify fs-7 semi-tight-spacing">
                                    {{ $dasar }}
                                </li>
                            @endforeach
                        @endif
                    </ol>
                @endif
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">Membaca</td>
            <td class="fs-6" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0">{{ $konten['membaca'] ?? 'BELUM DI SET' }}</p>
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
        {{ $konten['keterangan'] }}
    </p>
    @if (isset($konten['ketentuan']))
        <ol class="ps-3">
            @if (is_array($konten['ketentuan']))
                @foreach ($konten['ketentuan'] as $ketentuan)
                    <li class="text-justify fs-8">
                        {{ $ketentuan }}
                    </li>
                @endforeach
            @endif
        </ol>
    @endif
    <p class="mt-3 fs-6 normal-spacing">
        {{ $konten['penutup'] ?? 'BELUM DI SET' }}
    </p>

    <table>
        <tr>
            <td style="width: 50%">
                <img src="{{ $qrcode }}">
                <p class="fs-85 mb-0 mt-3">
                    Tembusan Yth.
                </p>
                @isset($konten['tembusan'])
                    <ol class="ps-3 mb-0">
                        @if (is_array($konten['tembusan']))
                            @foreach ($konten['tembusan'] as $tembusan)
                                <li class="fs-85">
                                    {{ $tembusan }}
                                </li>
                            @endforeach
                        @endif
                    </ol>
                @endisset
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