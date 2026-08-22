@extends('layouts.app')

@section('main')
<div class="min-h-screen grid place-items-center px-4 py-10">
    <div class="w-full max-w-[460px]">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('welcome') }}" class="h-10 w-10 rounded-xl bg-white ring-1 ring-ink-200 grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-8 w-8 object-contain"></a>
            <div><p class="font-display font-extrabold text-ink-900 leading-none">{{ strtoupper(Version::productName()) }}</p><p class="text-xs tracking-widest text-ink-500 uppercase">{{ Version::organizationName() }}</p></div>
        </div>

        <div class="rounded-[24px] bg-white p-6 sm:p-8 shadow-[0_20px_60px_rgba(15,23,42,0.08)] ring-1 ring-ink-200">
            @if (session('resent'))
                <div class="mb-5 flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 ring-1 ring-emerald-200" role="alert">
                    <i class="fa-solid fa-circle-check"></i> {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                </div>
            @endif

            <div class="mx-auto mb-5 grid h-14 w-14 place-items-center rounded-2xl bg-nest-50 text-nest-700 ring-8 ring-nest-50/60">
                <i class="fa-solid fa-envelope-open-text text-xl"></i>
            </div>
            <h1 class="text-center font-display font-extrabold text-xl text-ink-900">Verifikasi Email Anda</h1>
            <div class="mt-2 text-center text-sm leading-relaxed text-ink-600">
                {{ __('Sebelum melanjutkan, harap periksa email Anda untuk tautan verifikasi.') }}
                {{ __('Jika Anda tidak menerima email') }},
                <form class="inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="font-bold text-nest-700 underline-offset-2 hover:underline">{{ __('klik di sini untuk meminta lagi.') }}</button>.
                </form>
            </div>
        </div>

        <p class="mt-4 text-center text-xs text-ink-500">Butuh bantuan? Hubungi {{ Version::organizationEmail() }}</p>
    </div>
</div>
@endsection
