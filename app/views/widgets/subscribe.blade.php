<div class="row">
    <div class="col-md-12">
        <div id="subscription">
            @if(Auth::check())
                <div class="btn-group">
                    @if(!Auth::user()->subscribed($page->id))
                        <a href="javascript:void(0)" data-page-id="{{ $page->id }}" id="subscribe" class="btn btn-primary btn-sm">
                            <span class="text-link">Подписаться</span>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm subscribers">
                            {{ count($page->subscribers) }}
                        </a>
                    @else
                        <a href="javascript:void(0)" data-page-id="{{ $page->id }}" id="unsubscribe" class="btn btn-primary btn-sm">
                            <span class="text-link">Отменить подписку</span>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm subscribers">
                            {{ count($page->subscribers) }}
                        </a>
                    @endif
                </div>
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
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success){
                            $("#subscribe-message").text(response.message);
                            $link.find('.text-link').text('Отенить подписку');
                            $link.parent().find('.subscribers').text(response.subscribers);
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
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success){
                            $("#subscribe-message").text(response.message);
                            $link.find('.text-link').text('Подписаться');
                            $link.parent().find('.subscribers').text(response.subscribers);
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