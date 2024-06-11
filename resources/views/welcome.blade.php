@extends('layouts.app')
@section('main')
    <nav class="welcome-nav">
        <div class="container">
            <div class="d-flex justify-content-between">
                <div class="welcome-nav-logo">
                    <img src="{{ asset('storage/web/favicon.ico') }}" alt="icon">
                </div>
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <a href="{{ route('login') }}" class="nav-link btn-edit">Login</a>
                    <a href="{{ route('register') }}" class="nav-link">
                        <button class="btn btn-app-primary rounded-pill">
                            Register
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <section class="mt-nav container">
        <div class="row pt-5 align-items-center">
            <div class="col-md-5 col-sm-8">
                <h2 class="page-title text-uppercase">{{ env('APP_NAME') }}</h2>
                <p class="text-muted">Selamat datang di Aplikasi Perizinan Online Dinas Penanaman Modal dan PTSP Kabupaten Bone Bolango! Kami hadir untuk mempermudah proses perizinan Anda dengan layanan yang cepat dan mudah.</p>
                <button class="btn btn-app-primary px-4 rounded-pill">Daftar Izin Baru</button>
            </div>
            <div class="col-md-7 col-sm-4 text-end">
                <img src="{{ asset('storage/web/img-front2.png') }}" alt="front-img" class="img-fluid" height="600" width="600">
            </div>
        </div>
    </section>
    <section class="bg-app-primary mt-4">
        <div class="container py-5">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium ab ad, quasi enim fuga facilis velit quidem obcaecati id dolorum pariatur debitis sequi optio numquam iste corporis minima delectus veritatis consectetur ullam. Sequi perferendis nam voluptatem impedit error excepturi ipsum quae recusandae delectus, incidunt aut cumque reiciendis quod dolore laborum minus quasi? Esse consequuntur nam nemo fugit ex dolor, iure possimus officia recusandae eligendi voluptas eaque suscipit ea expedita! Sapiente accusamus dolor facere doloribus beatae soluta, nemo saepe officia voluptatem iure, eum odit ea! Sunt, assumenda nobis quod, dignissimos nam culpa impedit, corrupti placeat odit beatae necessitatibus totam sapiente facilis.
            <hr class="border border-3">
            <aside class="d-flex justify-content-center">
                <h6 class="fw-bold text-uppercase">contact us</h6>
            </aside>
        </div>
    </section>
@endsection