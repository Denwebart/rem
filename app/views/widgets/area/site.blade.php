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
                    <div class="advertising access-{{ $item->access }}{{ $item->is_active ? '' : ' not-active'}}" {{ $item->is_active ? '' : 'style="display: none"'}} data-advertising-id="{{ $item->id }}">
                    @include('widgets.area.controlAdvertising')
                @else
                    <div class="advertising">
                @endif
            @else
                <div class="advertising">
            @endif
                <div class="advertising-body">
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

        $('.advertising').on('click', '.change-active-status', function(){
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
                        console.log($('[data-advertising-id='+ advertisingId +']'));
                        if(response.isActive) {
                            $('[data-advertising-id='+ advertisingId +']').removeClass('not-active');
                            $button.attr('title', 'Выключить этот рекламный блок на этой старинце.').html('<span class="mdi-action-visibility-off"></span>');
                        } else {
                            $('[data-advertising-id='+ advertisingId +']').addClass('not-active');
                            $button.attr('title', 'Включить этот рекламный блок на этой старинце.').html('<span class="mdi-action-visibility"></span>');
                        }
                        $button.attr('data-is-active', response.isActive);
                    } else {
                        alert(response.message)
                    }
                }
            });
        });

    </script>
@endsection