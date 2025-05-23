@extends('templates.layouts.template-app')
@push('margin-paper')px-3 @endpush
@section('content')
    <p class="fs-5 text-center text-decoration-underline mb-0 pt-3">
        {{ $konten['judul'] ?? 'BELUM DI SET' }}
    </p>
    <p class="fw-bold text-center normal-spacing"> Nomor : {{ $izin->no_surat }}</p>

    <img src="data:image;base64,{{ base64_encode(file_get_contents(public_path('storage/pdf-assets/free.png'))) }}" alt="free" class="free-watermark-right">

    <p class="fs-6 text-justify">
        {{ $konten['dasar'] ?? 'BELUM DI SET' }}
    </p>

    <p class="text-center text-decoration-underline fs-4 fw-bold">
        {{ $izin->pemohon->nama }}
    </p>

    <table>
        <tr>
            <td class="fs-6" style="width: 25%">
                Tempat / Tgl. Lahir
            </td>
            <td class="fs-6" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    {{ $data_izin['tempat tanggal lahir'] ?? 'BELUM DI SET' }}
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
                    {{ convert_address($izin->pemohon->getAlamat()) }}
                </p>
            </td>
        </tr>
        <tr>
            <td class="fs-6 pt-2" style="width: 25%">
                Nomor STR-ATLM
            </td>
            <td class="fs-6 pt-2" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    {{ $data_izin['nomor str atlm'] ?? 'BELUM DI SET' }}
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="py-4">
                {{ $konten['keterangan'] ?? 'BELUM DI SET' }}
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 25%">
                Tempat Praktik
            </td>
            <td class="fs-6" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    {{ $data_izin['tempat praktik'] ?? 'BELUM DI SET' }}
                </p>
            </td>
        </tr>
        <tr>
            <td class="fs-6" style="width: 25%">
                Alamat Praktik
            </td>
            <td class="fs-6" style="width: 75%">
                <span style="float: left">:</span>
                <p class="ms-2 mb-0 text-uppercase">
                    {{ $data_izin['alamat tempat praktik'] ?? 'BELUM DI SET' }}
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="py-4">
                {{ isset($konten['judul']) ? str_capitalize($konten['judul'], true) : 'BELUM DI SET' }} ini berlaku sampai dengan tanggal {{ $data_izin['masa berlaku str alm'] ?? 'BELUM DI SET' }}
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width: 50%" class="pt-5">
                <img src="{{ $qrcode }}" class="mt-3">
                <p class="fs-6 mb-0 mt-5">
                    Tembusan Yth.
                </p>
                @isset($konten['tembusan'])
                    <ol class="ps-3 mb-0">
                        @if (is_array($konten['tembusan']))
                            @foreach ($konten['tembusan'] as $tembusan)
                                <li class="fs-6">
                                    {{ $tembusan }}
                                </li>
                            @endforeach
                        @endif
                    </ol>
                @endisset
            </td>
            <td style="width: 50%" class="pt-5 text-center">
                <p class="fs-55 mb-1 normal-spacing">{{ $kop_surat['tempat_penetapan'] ?? 'BELUM DI SET' }}, {{ $data_izin['tanggal penetapan'] ?? 'BELUM DI SET' }}</p>
                <p class="fs-55 normal-spacing text-uppercase">{{ $izin->penandatangan->jabatan }}</p>
                <p class="fs-55 mt-5 pt-3 normal-spacing text-decoration-underline mb-0">{{ $izin->penandatangan->nama }}</p>
                <p class="fs-55 mb-0 normal-spacing">{{ $izin->penandatangan->pangkat }}</p>
                <p class="fs-55 normal-spacing">Nip. {{ $izin->penandatangan->nip }}</p>
            </td>
        </tr>
    </table>
@endsection