@include('templates.layouts.header')
@include('templates.layouts.cop')
<div class="pdf-body">
    @yield('content')
</div>
@include('templates.layouts.footer')