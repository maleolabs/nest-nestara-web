@extends('layouts.app')

@section('main')
<div class="page-header align-items-start min-vh-100">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
        <div class="row">
            <div class="col-lg-4 col-md-8 col-12 mx-auto">
                <div class="card z-index-0 fadeIn3 fadeInBottom">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1 d-flex flex-column align-items-center">
                            <a href="{{ route('welcome') }}" class="text-decoration-none">
                                <img src="{{ asset('storage/web/favicon.ico') }}" alt="mpp-bonebol" class="img-fluid">
                            </a>
                            <h5 class="text-white fw-bold text-center mt-2 mb-0 spacing-normal">{{ __('Verifikasi Alamat Email') }}</h5>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        @if (session('resent'))
                            <div class="border border-success rounded text-success p-2 mb-3 bg-success-light" role="alert">
                                {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                            </div>
                        @endif

                        {{ __('Sebelum melanjutkan, harap periksa email Anda untuk tautan verifikasi.') }}
                        {{ __('Jika Anda tidak menerima email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('klik di sini untuk meminta lagi.') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
