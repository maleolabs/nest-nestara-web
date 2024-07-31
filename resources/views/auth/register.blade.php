@extends('layouts.app')

@section('main')

<div class="page-header page-register min-vh-100">
    <div class="container">
        <div class="row">
            <div
                class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
                <div class="page-img position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center">
                </div>
            </div>
            <div
                class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
                <div class="card card-plain">
                    <div class="card-header">
                        <h4 class="font-weight-bolder">Daftar</h4>
                        <p class="mb-0">Masukkan username dan password anda untuk mendaftar</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="input-group input-group-outline @error('name') mb-5 is-invalid @elseif(old('name')) is-valid @enderror mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="position-absolute top-100 ms-2">
                                        <small class="text-danger">
                                            <i class="fa-solid fa-circle-info me-2"></i>
                                            {{ $message }}
                                        </small>
                                    </div>
                                @enderror
                            </div>
                            <div class="input-group input-group-outline mb-3 @error('username') mb-5 is-invalid @elseif(old('username')) is-valid @enderror">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                                @error('username')
                                    <div class="position-absolute top-100 ms-2">
                                        <small class="text-danger">
                                            <i class="fa-solid fa-circle-info me-2"></i>
                                            {{ $message }}
                                        </small>
                                    </div>
                                @enderror
                            </div>
                            <div class="input-group input-group-outline mb-3 @error('password') mb-5 is-invalid @enderror">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                                @error('password')
                                    <div class="position-absolute top-100 ms-2">
                                        <small class="text-danger">
                                            <i class="fa-solid fa-circle-info me-2"></i>
                                            {{ $message }}
                                        </small>
                                    </div>
                                @enderror
                            </div>
                            <div class="input-group input-group-outline mb-3 @error('password_confirmation') is-invalid @enderror">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                @error('password_confirmation')
                                    <div class="text-danger fs-6 d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit"
                                    class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">
                                    Daftar
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                        <p class="mb-2 text-sm mx-auto">
                            Sudah mempunyai akun?
                            <a href="{{ route('login') }}"
                                class="text-primary text-gradient font-weight-bold">Masuk</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
