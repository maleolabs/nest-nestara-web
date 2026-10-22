@extends('layouts.user-app')
@section('main')
    <div class="row justify-center">
        <div class="col-md-5">
            <div class="card p-4">
                <div class="card-body">
                    <div class="flex flex-col gap-2 items-center justify-center">
                        @if ($message)
                            <i class="fa-solid fa-xmark-circle fs-2 text-red-600"></i>
                            <h5 class="font-bold text-red-600 mb-0">{{ $message }}</h5>
                            <p class="text-sm text-center mb-0">
                                Maaf, izin yang Anda coba periksa tidak ditemukan dalam sistem kami. Pastikan QR code yang Anda pindai berasal dari sumber yang resmi dan benar. Jika Anda memerlukan bantuan lebih lanjut, silakan hubungi layanan pelanggan kami dibawah ini. Terima kasih.
                            </p>
                            <p class="text-sm font-bold mb-0">
                                <i class="fa-solid fa-phone me-1"></i>
                                0800000000000
                            </p>
                            <p class="text-sm font-bold mb-0">
                                <i class="fa-solid fa-envelope me-1"></i>
                                {{ Version::organizationEmail() }}
                            </p>
                        @elseif ($izin)
                            <i class="fa-solid fa-circle-check fs-2 text-emerald-600"></i>
                            <h5 class="font-bold text-emerald-600 mb-0">Izin Terdaftar</h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection