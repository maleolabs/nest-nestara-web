@include('templates.layouts.header')
<div class="pdf-body @stack('margin-paper')">
    @include('templates.layouts.cop')
    @yield('content')
</div>
@include('templates.layouts.footer')