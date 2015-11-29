<div class="row">
    <div class="area area-site">
        @if(Auth::check())
            @if(Auth::user()->isAdmin())
                @include('widgets.area.create')
            @endif
        @endif
        @foreach($advertising as $item)
            @if(Auth::check())
                @if(Auth::user()->isAdmin())
                    <div class="widget access-{{ $item->access }}{{ $item->is_active ? '' : ' not-active'}}" {{ $item->is_active ? '' : 'style="display: none"'}} data-widget-id="{{ $item->id }}">
                    <div class="widget-title" style="display: none">
                        <a href="{{ URL::route('admin.advertising.index', ['id' => $item->id]) }}" title="Смотреть в админке" data-toggle="tooltip">
                            {{ $item->title }}
                        </a>
                    </div>
                @else
                    <div class="widget">
                @endif
            @else
                <div class="widget">
            @endif
                <div class="widget-body">
                    @include('widgets.area.controlAdvertising')
                    @if($item->is_show_title)
                        <h4>{{ $item->title }}</h4>
                    @endif
                    @if(Advertising::TYPE_ADVERTISING == $item->type)
                        {{ $item->code }}
                    @elseif(Advertising::TYPE_WIDGET == $item->type)
                        <?php $sidebarWidget = app('SidebarWidget')?>
                        {{ $sidebarWidget->show($item->code, $item->limit) }}
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@section('script')
    @parent

    <script type="text/javascript">

        $('.widget').on('click', '.change-active-status', function(){
            var $button = $(this),
                isActive = $button.attr('data-is-active'),
                advertisingId = $button.data('id');
            $.ajax({
                url: '/admin/advertising/changeActiveStatus/' + advertisingId,
                dataType: "text json",
                type: "POST",
                data: {is_active: isActive},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $('#site-messages').prepend(response.message);
                        setTimeout(function() {
                            hideSiteMessage($('.site-message'));
                        }, 2000);

                        if(response.isActive) {
                            $('[data-widget-id='+ advertisingId +']').removeClass('not-active');
                            $button.attr('title', 'Выключить этот рекламный блок на этой старинце.').html('<i class="material-icons">visibility_off</i>');
                        } else {
                            $('[data-widget-id='+ advertisingId +']').addClass('not-active');
                            $button.attr('title', 'Включить этот рекламный блок на этой старинце.').html('<i class="material-icons">visibility</i>');
                        }
                        $button.attr('data-is-active', response.isActive);
                    } else {
                        $('#site-messages').prepend(response.message);
                        setTimeout(function() {
                            hideSiteMessage($('.site-message'));
                        }, 2000);
                    }
                }
            });
        });
    </script>
@stop