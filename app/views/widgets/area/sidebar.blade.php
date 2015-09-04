<div class="row">
    <div class="area area-sidebar">
        @if(Auth::check())
            @if(Auth::user()->isAdmin())
                @include('widgets.area.create')
            @endif
        @endif
        @foreach($advertising as $item)
            <div class="col-md-12 col-sm-6 col-xs-12 without-padding">
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
            </div>
        @endforeach
    </div>
</div>