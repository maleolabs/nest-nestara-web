<section class="sticky-top-0 z-nav bg-white py-3">
    <div class="container-fluid d-flex flex-wrap align-items-start justify-content-between">
        <div class="d-sm-flex d-none align-items-center gap-3">
            <button class="btn rounded m-0 border px-2 py-1 btn-show-left-menu">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <a href="{{ route('welcome') }}" title="Dinas PMPTSP">
                <img src="{{ asset('storage/web/favicon.ico') }}" width="35" height="35" class="img-fluid">
            </a>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('profile') }}">
                            <i class="fa-solid fa-at"></i>
                            @auth
                                {{ auth()->user()->username }}
                            @else
                                Guest
                            @endauth
                        </a>
                    </li>
                    @if (str_contains(Route::currentRouteName(), 'dashboard'))
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @endif
                    @stack('breadcrumb-item')
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-3 @stack('nav-content-width') justify-content-end">
            @stack('nav-dashboard-content')
            <a href="javascript:;" class="sidebar-btn-show position-relative">
                <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="" class="profile-img">
            </a>
        </div>
    </div>
</section>
@include('layouts.right-sidebar')
@include('layouts.left-menu')