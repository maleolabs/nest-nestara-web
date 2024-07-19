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
                @isset($konten['dasar'])
                    <ol class="ps-4 mb-0">
                        @if (is_array($konten['dasar']) && count($konten['dasar']) > 0)
                            @foreach ($konten['dasar'] as $dasar)
                                <li class="text-justify fs-7 semi-tight-spacing">
                                    {{ $dasar }}
                                </li>
                            @endforeach
                        @endif
                    </ol>
                @endisset
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
                <p class="ms-2 mb-0 text-justify">{{ $data_izin['menimbang'] ?? 'BELUM DI SET' }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p class="fs-5 text-center mb-0">MENERANGKAN</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">a. Nama/Nim</td>
            <td class="fs-6 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                @isset($data_izin['anggota peneliti'])
                    @if (is_array($data_izin['anggota peneliti']))
                        <ol class="ps-4 mb-0">
                            @foreach ($data_izin['anggota peneliti'] as $peneliti)
                                <li>{{ $peneliti }}</li>
                            @endforeach
                        </ol>
                    @else
                        <p class="ms-2 mb-0">{{ $data_izin['anggota peneliti'] }}</p>
                    @endif                    
                @endisset
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
                <p class="ms-2 mb-0 text-justify">{{ $data_izin['judul penelitian'] ?? 'BELUM DI SET' }}</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6 pt-4" style="width: 18%">d. Tujuan Penelitian</td>
            <td class="fs-8 pt-4 tight-spacing pt-4 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">{{ $data_izin['tujuan penelitian'] ?? 'BELUM DI SET' }}</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">e. Tempat Penelitian</td>
            <td class="fs-8 tight-spacing text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">{{ $data_izin['tempat penelitian'] ?? 'BELUM DI SET' }}</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6 pt-4" style="width: 18%">f. Waktu Penelitian</td>
            <td class="fs-6 pt-4 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">{{ $data_izin['waktu penelitian'] ?? 'BELUM DI SET' }}</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">g. Bidang Penelitian</td>
            <td class="fs-6 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">{{ $data_izin['bidang penelitian'] ?? 'BELUM DI SET' }}</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">h. Status Penelitian</td>
            <td class="fs-6 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">{{ $data_izin['status penelitian'] ?? 'BELUM DI SET' }}</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">i. Penanggungjawab</td>
            <td class="fs-6" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">{{ $data_izin['penanggungjawab'] ?? 'BELUM DI SET' }}</p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 18%">k. Nama Lembaga</td>
            <td class="fs-6 text-uppercase" style="width: 82%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-justify">{{ $data_izin['nama lembaga'] ?? 'BELUM DI SET' }}</p>
            </td>
        </tr>
    </table>

    <p class="fs-7 mt-3 mb-2">
        {{ $konten['keterangan'] }}
    </p>
    @isset($konten['ketentuan'])
        <ol class="ps-3">
            @if (is_array($konten['ketentuan']))
                @foreach ($konten['ketentuan'] as $ketentuan)
                    <li class="text-justify fs-8">
                        {{ $ketentuan }}
                    </li>
                @endforeach
            @endif
        </ol>
    @endisset
    <p class="mt-3 fs-6 normal-spacing">
        {{ $konten['penutup'] ?? 'BELUM DI SET' }}
    </p>

    <table>
        <tr>
            <td style="width: 50%">
                <img src="{{ $qrcode }}" class="mt-2">
                <p class="fs-85 mb-0 mt-4">
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
                <p class="fs-55 mb-1 normal-spacing">{{ $kop_surat['tempat_penetapan'] ?? 'BELUM DI SET' }}, {{ $data_izin['tanggal penetapan'] ?? 'BELUM DI SET' }}</p>
                <p class="fs-55 normal-spacing">KEPALA DINAS</p>
                <p class="fs-55 mt-5 pt-2 normal-spacing text-decoration-underline mb-0">JUMAIDIL, AP, S.Sos,M.Ec.Dev</p>
                <p class="fs-55 mb-0 normal-spacing">Pembina Utama Muda</p>
                <p class="fs-55 normal-spacing">Nip. 19741018 199311 1 002</p>
            </td>
        </tr>
    </table>
@endsection