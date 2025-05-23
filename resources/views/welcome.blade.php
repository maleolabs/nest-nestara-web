@extends('layouts.user-app')
@section('main')
    <section class="mt-nav container">
        <div class="row pt-5 align-items-center">
            <div class="col-md-5 col-sm-8">
                <h2 class="page-title text-uppercase">{{ env('APP_NAME') }}</h2>
                <p class="text-muted">Selamat datang di Aplikasi Perizinan Online Dinas Penanaman Modal dan PTSP Kabupaten Bone Bolango! Kami hadir untuk mempermudah proses perizinan Anda dengan layanan yang cepat dan mudah.</p>
                @if (!auth()->check() || auth()->user()->hasRole('pemohon'))
                    <a href="{{ route('create-izin') }}" class="btn btn-app-primary px-4 rounded-pill">Daftar Izin Baru</a>
                @endif
            </div>
            <div class="col-md-7 col-sm-4 text-end d-sm-block d-none">
                <img src="{{ asset('storage/web/img-front2.png') }}" alt="front-img" class="img-fluid" height="600" width="600">
            </div>
        </div>
    </section>
    <section class="bg-app-primary mt-4">
        <div class="container py-5">
            
            <div class="d-flex flex-column align-items-center">
                <h5 class="fw-semibold mb-3">Sistem Informasi Perizinan Dinas PMPTSP Bone Bolango</h5>
                <p class="text-center">
                    Selamat datang di Sistem Informasi Perizinan PMPTSP. Kami berkomitmen untuk menyediakan layanan perizinan yang transparan, efisien, dan mudah diakses oleh masyarakat. Melalui platform ini, pemohon dapat mendaftar, mengunggah dokumen, memantau status permohonan, dan memperoleh informasi terkini mengenai proses perizinan.
                </p>
                <p class="text-center">
                    Kami berupaya untuk terus meningkatkan kualitas layanan dan memberikan pengalaman terbaik bagi Anda. Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi kami.
                </p>
                <p class="text-center text-bold mb-1">Jam Operasional</p>
                <p class="text-center">Senin - Jumat: 08.00 - 16.00 WITA</p>
            </div>

            <hr class="border border-3">
            <aside class="d-flex flex-column align-items-center">
                <h6 class="fw-bold text-uppercase">contact us</h6>
                <p class="fw-semibold mb-1">Email: support@perizinanpmptsp.go.id</p>
                <p class="fw-semibold mb-1">Telepon: (021) 123-4567</p>

                <p class="text-sm fw-medium mt-3">Terima kasih telah menggunakan layanan kami.</p>
            </aside>
        </div>
    </section>
@endsection