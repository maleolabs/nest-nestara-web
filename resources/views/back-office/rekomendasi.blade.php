@extends('back-office.layouts.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('pegawai.rekomendasi') }}">
            Rekomendasi
        </a>
    </li>
    <li class="breadcrumb-item active hidden" id="rekomendasi-sub-breadcrumb"></li>
@endpush
@section('main')
    @livewire('dashboard.back-office.rekomendasi')
@endsection
@push('scripts')
    <script>
        let rekomSubBread = document.getElementById("rekomendasi-sub-breadcrumb")
        Livewire.on('preview-selected', function (event) {
            rekomSubBread.classList.remove("hidden")
            rekomSubBread.textContent = event[0].preview
        })
    </script>
@endpush