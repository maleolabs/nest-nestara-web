@extends('layouts.app')

@section('main')
<div>
    <div class="img-full-vh"></div>
</div>
<div class="position-absolute top-50 start-50 translate-middle w-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="dash-card-mono min-vh-50 p-4 shadow">
                    <h5 class="text-center fw-bold">
                        <a href="{{ route('welcome') }}" class="text-decoration-none text-dark">
                            {{ env('APP_NAME') }}
                        </a>
                        {{ ' | ' . __('Login') }}
                    </h5>
    
                    <div class="mt-4 px-sm-5">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
    
                            <div class="mb-3">
                                <label for="email" class="d-block">{{ __('Email Address') }}</label>
                                <div class="mat-app-input">
                                    <i class="fa-solid fa-envelope"></i>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="mb-3">
                                <label for="password" class="d-block">{{ __('Password') }}</label>
    
                                <div class="mat-app-input">
                                    <i class="fa-solid fa-lock"></i>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="d-flex flex-column align-items-center gap-3">
                                <button type="submit" class="btn btn-app-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('register'))
                                    <a class="btn btn-link" href="{{ route('register') }}">
                                        {{ __('Belum mempunyai akun? Register') }}
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
@push('scripts')
    <script>
        function setViewBox() {
            const svg = document.getElementById('responsive-svg');
            const width = window.innerWidth;
            const height = window.innerHeight;
            svg.setAttribute('viewBox', `0 0 ${width} ${height}`);
        }

        window.addEventListener('resize', setViewBox);
        window.addEventListener('load', setViewBox);
    </script>
@endpush