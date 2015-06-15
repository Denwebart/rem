<div class="row">
    <div class="col-md-12">
        <div id="subscription">
            @if(Auth::check())
                @if(!Auth::user()->subscribed($page->id))
                    <a href="javascript:void(0)" id="subscribe" data-page-id="{{ $page->id }}" class="btn btn-success pull-right">
                    <span class="text-link">
                        Подписаться
                    </span>
                    <span class="subscribers">
                        {{ count($page->subscribers) }}
                    </span>
                    </a>
                @else
                    <a href="javascript:void(0)" id="unsubscribe" data-page-id="{{ $page->id }}" class="btn btn-success pull-right">
                    <span class="text-link">
                        Вы подписаны
                    </span>
                    <span class="subscribers">
                        {{ count($page->subscribers) }}
                    </span>
                    </a>
                @endif
            @endif
        </div>
        <div id="subscribe-message"></div>
    </div>
</div>

@if(Auth::check())
    @section('script')
        @parent

        <script type="text/javascript">
            $("#subscription").on('click', '#subscribe', function() {
                var $link = $(this);
                var pageId = $link.data('pageId');
                $.ajax({
                    url: "{{ URL::route('user.subscribe', ['login' => Auth::user()->getLoginForUrl()]) }}",
                    dataType: "text json",
                    type: "POST",
                    data: {pageId: pageId},
                    success: function(response) {
                        if(response.success){
                            $("#subscribe-message").text(response.message);
                            $link.find('.text-link').text('Вы подписаны');
                            $link.find('.subscribers').text(response.subscribers);
                            $link.attr('id', 'unsubscribe');
                        } else {
                            $("#subscribe-message").text(response.message);
                        }
                    }
                });
            });

            $("#subscription").on('click', '#unsubscribe', function() {
                var $link = $(this);
                var pageId = $link.data('pageId');
                $.ajax({
                    url: "{{ URL::route('user.unsubscribe', ['login' => Auth::user()->getLoginForUrl()]) }}",
                    dataType: "text json",
                    type: "POST",
                    data: {pageId: pageId},
                    success: function(response) {
                        if(response.success){
                            $("#subscribe-message").text(response.message);
                            $link.find('.text-link').text('Подписаться');
                            $link.find('.subscribers').text(response.subscribers);
                            $link.attr('id', 'subscribe');
                        } else {
                            $("#subscribe-message").text(response.message);
                        }
                    }
                });
            });
        </script>
    @stop
@endif