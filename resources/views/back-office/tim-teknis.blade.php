@extends('back-office.layouts.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('pegawai.tim-teknis') }}">
            Tim Teknis
        </a>
    </li>
    <li class="breadcrumb-item active" id="teim-teknis-sub-breadcrumb"></li>
@endpush
@section('main')
    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sapiente exercitationem eaque error, odit veniam unde nesciunt voluptatum quos recusandae deleniti. Ducimus ratione consequatur ex error culpa ab ad quo earum.
@endsection