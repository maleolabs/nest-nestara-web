@extends('layouts.user-app')
@section('main')
    <div class="container">
        <div class="row">
            @foreach ($all_perizinan as $perizinan)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="form-card shadow p-3">
                        <h6>{{ $perizinan->nama }}</h6>
                    </div>
                </div>
            @endforeach
            {{ $all_perizinan->links() }}
        </div>
    </div>
@endsection