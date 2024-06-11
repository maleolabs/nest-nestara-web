@extends('layouts.app')

@section('main')
<div>
    <div class="img-full-vh"></div>
</div>
<div class="position-absolute top-50 start-50 translate-middle w-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 p-4">
                <div class="dash-card-mono shadow">
                    <h5 class="text-center fw-bold">
                        <a href="{{ route('welcome') }}" class="text-decoration-none text-dark">
                            {{ env('APP_NAME') }}
                        </a>
                        {{ ' | ' . __('Register') }}
                    </h5>

                    <div class="my-4 px-sm-5">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>

                                <div class="mat-app-input">
                                    <i class="fa-solid fa-user"></i>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>

                                <div class="mat-app-input">
                                    <i class="fa-solid fa-envelope"></i>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>

                                <div class="mat-app-input">
                                    <i class="fa-solid fa-lock"></i>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>

                                <div class="mat-app-input">
                                    <i class="fa-solid fa-lock"></i>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="mb-4 d-flex flex-column align-items-center gap-3">
                                <button type="submit" class="btn btn-app-primary">
                                    {{ __('Register') }}
                                </button>
                                @if (Route::has('login'))
                                    <a class="btn btn-link" href="{{ route('login') }}">
                                        {{ __('Sudah mempunyai akun? Login') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="position-absolute pb-2 bottom-0 start-0 w-100">
                        <div class="text-muted d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-copyright"></i>
                            <span class="ms-1">marijmokoginta</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
