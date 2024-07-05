<section class="sticky-top-0 z-nav bg-white py-3">
    <div class="container-fluid d-flex flex-wrap align-items-start justify-content-between">
        <div class="d-sm-flex d-none align-items-center gap-3">
            <button class="btn rounded m-0 border px-2 py-1 btn-show-left-menu">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <img src="{{ asset('storage/web/favicon.ico') }}" width="35" height="35" class="img-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item text-capitalize">
                        <a href="javascript:;">
                            @auth
                                {{ auth()->user()->name }}
                            @else
                                Guest
                            @endauth
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    @stack('breadcrumb-item')
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-3">
            @stack('nav-dashboard-content')
            <a href="javascript:;" class="sidebar-btn-show position-relative">
                <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="" class="profile-img">
            </a>
        </div>
    </div>
</section>
@include('layouts.right-sidebar')
@include('layouts.left-menu')