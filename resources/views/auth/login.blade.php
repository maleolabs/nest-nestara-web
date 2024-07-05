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
                            <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Silahkan Masuk</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form role="form" class="text-start" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="input-group input-group-outline my-3 @error('email') is-invalid @enderror">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Masuk</button>
                            </div>
                            <p class="mt-4 text-sm text-center">
                                Belum mempunyai akun?
                                <a href="{{ route('register') }}"
                                    class="text-primary text-gradient font-weight-bold">Daftar</a>
                            </p>
                        </form>
                        {{-- <p class="text-xs text-bold fst-italic text-center">{!! $quote !!}</p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection