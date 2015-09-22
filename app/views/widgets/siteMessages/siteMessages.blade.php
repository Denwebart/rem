<div id="site-messages">
    @if(Session::has('errorMessage'))
        @include('widgets.siteMessages.danger', ['siteMessage' => Session::get('errorMessage')])
    @endif
    @yield('siteMessages')
</div>

@section('script')
    @parent

    <script type="text/javascript">
        $('#site-messages').on('click', '.site-message', function() {
            var $message = $(this);
            $message.show("slow").animate({ right: "-=1000" }, 1000 );

            setTimeout(function() {
                $message.remove();
            }, 2000);
        })
    </script>
@stop