@extends('layouts.user-app')
@section('main')
    @livewire('user.revisi-izin', ['izin_id' => $izin_id])
@endsection