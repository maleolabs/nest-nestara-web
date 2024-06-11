@include('layouts.header')
@include('layouts.welcome-nav')
@auth
@include('layouts.right-sidebar')
@endauth
<body>
    @yield('main')
</body>

@include('layouts.footer')