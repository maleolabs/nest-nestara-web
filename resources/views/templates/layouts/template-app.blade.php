@include('templates.layouts.header')
<div class="pdf-body">
    @include('templates.layouts.cop')
    @yield('content')
</div>
@include('templates.layouts.footer')