@include('templates.layouts.header')
<div class="pdf-body @stack('margin-paper')">
    @include('templates.layouts.kop')
    @yield('content')
</div>
@include('templates.layouts.footer')