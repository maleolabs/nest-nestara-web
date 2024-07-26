@include('layouts.header')
<body class="bg-light">
    @include('dashboard.dashboard-nav')
    <main class="container-fluid pt-4">
        {{ $slot }}
    </main>
</body>
@include('layouts.footer')