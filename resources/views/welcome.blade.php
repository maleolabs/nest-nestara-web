@extends('layouts.app')
@section('main')
    <nav class="welcome-nav">
        <div class="container">
            <div class="d-flex justify-content-between">
                <span class="welcome-nav-logo"></span>
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <a href="#" class="nav-link">Login</a>
                    <a href="#" class="nav-link">
                        <button class="btn btn-app-primary rounded-pill">
                            Register
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <section>
        <div class="container position-absolute top-50 start-50 translate-middle">
            <div class="row pt-5">
                <div class="col-md-6 col-sm-8">
                    <h2 class="page-title text-uppercase">{{ env('APP_NAME') }}</h2>
                    <p>Selamat datang di Aplikasi Perizinan Online Dinas Penanaman Modal dan PTSP Kabupaten Bone Bolango! Kami hadir untuk mempermudah proses perizinan Anda dengan layanan yang cepat dan mudah.</p>
                    <button class="btn btn-app-primary px-4 rounded-pill">Daftar Izin Baru</button>
                </div>
                <div class="col-md-6 col-sm-4">
                    <img src="{{ asset('storage/web/img-front.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection