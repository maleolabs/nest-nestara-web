@extends('layouts.app')

@section('main')
<div class="min-h-screen grid place-items-center px-4 py-10">
    <div class="w-full max-w-[460px]">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('welcome') }}" class="h-10 w-10 rounded-xl bg-white ring-1 ring-ink-200 grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-8 w-8 object-contain"></a>
            <div><p class="font-display font-extrabold text-ink-900 leading-none">{{ strtoupper(Version::productName()) }}</p><p class="text-xs tracking-widest text-ink-500 uppercase">{{ Version::organizationName() }}</p></div>
        </div>

        <div class="rounded-[24px] bg-white p-6 sm:p-8 shadow-[0_20px_60px_rgba(15,23,42,0.08)] ring-1 ring-ink-200">
<form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
        </div>

        <p class="mt-4 text-center text-xs text-ink-500">Butuh bantuan? Hubungi {{ Version::organizationEmail() }}</p>
    </div>
</div>
@endsection
