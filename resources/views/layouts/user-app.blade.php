@include('layouts.header')
@auth
@include('layouts.right-sidebar')
@endauth
<body class="{{ Route::currentRouteName() == 'create-izin' || Route::currentRouteName() == 'user.izin' || Route::currentRouteName() == 'jenis-perizinan' ? 'bg-light' : '' }}">
    @include('layouts.welcome-nav')
    <main class="mt-welcome-nav">
        @yield('main')
    </main>
</body>

@include('layouts.footer')