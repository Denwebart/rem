<div id="site-messages">
    @if(Session::has('errorMessage'))
        @include('widgets.siteMessages.danger', ['siteMessage' => Session::get('errorMessage')])
    @endif
    @if(Session::has('warningMessage'))
        @include('widgets.siteMessages.warning', ['siteMessage' => Session::get('warningMessage')])
    @endif
    @if(Session::has('infoMessage'))
        @include('widgets.siteMessages.info', ['siteMessage' => Session::get('infoMessage')])
    @endif
    @if(Session::has('successMessage'))
        @include('widgets.siteMessages.success', ['siteMessage' => Session::get('successMessage')])
    @endif
    @yield('siteMessages')
</div>

@section('script')
    @parent

    <script type="text/javascript">
        // скрыть всплывающее сообщение
        function hideSiteMessage($message) {
            $message.show("slow").animate({ right: "-=1000" }, 1000 );

            setTimeout(function() {
                $message.remove();
            }, 2000);
        }

        $('#site-messages').on('click', '.site-message', function() {
            var $message = $(this);
            hideSiteMessage($message);
        });

        $( document ).ready(function() {
            setTimeout(function() {
                hideSiteMessage($('.site-message'));
            }, 2000);
        });
    </script>
@stop