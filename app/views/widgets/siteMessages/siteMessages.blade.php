<div id="site-messages">
    @if(Session::has('errorMessage'))
        @include('widgets.siteMessages.danger', ['siteMessage' => Session::get('errorMessage')])
    @endif
    @yield('siteMessages')
</div>