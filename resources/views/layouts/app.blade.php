@include('partials.topbar')

@yield('landing')

<div class="main">
    
    @yield('main')

    @include('partials.footer')
</div>

@yield('extra-scripts')