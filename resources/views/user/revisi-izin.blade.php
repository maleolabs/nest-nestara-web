@extends('layouts.user-app')
@section('main')
    @livewire('user.izin.revisi-izin', ['izin_id' => $izin_id])
@endsection