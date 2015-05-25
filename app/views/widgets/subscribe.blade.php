<div class="row">
    <div class="col-md-12">
        <div id="subscription">
            @if(!Auth::user()->subscribed($page->id))
                <a href="javascript:void(0)" id="subscribe" data-page-id="{{ $page->id }}" class="btn btn-success pull-right">
                    {{--<i class="glyphicon glyphicon-floppy-save"></i>--}}
                    Подписаться
                </a>
            @else
                <a href="javascript:void(0)" id="unsubscribe" data-page-id="{{ $page->id }}" class="btn btn-success pull-right">
                    {{--<i class="glyphicon glyphicon-floppy-remove"></i>--}}
                    Отписаться
                </a>
            @endif
        </div>
        <div id="subscribe-message"></div>
    </div>
</div>

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
//                        $link.find('i').attr('class', 'glyphicon glyphicon-floppy-remove');
                        $link.text('Отписаться');
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
//                        $link.find('i').attr('class', 'glyphicon glyphicon-floppy-save');
                        $link.text('Подписаться');
                        $link.attr('id', 'subscribe');
                    } else {
                        $("#subscribe-message").text(response.message);
                    }
                }
            });
        });
    </script>
@endsection