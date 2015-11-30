<div class="row">
    <div class="col-md-12">
        <div class="area area-content">
            @if(Auth::check())
                @if(Auth::user()->isAdmin())
                    @include('widgets.area.create')
                @endif
            @endif
            @foreach($advertising as $item)
                @if(Auth::check())
                    @if(Auth::user()->isAdmin())
                        <div class="widget access-{{ $item->access }}{{ $item->is_active ? '' : ' not-active'}} @if(Advertising::TYPE_ADVERTISING == $item->type) type-a @endif" {{ $item->is_active ? '' : 'style="display: none"'}} data-widget-id="{{ $item->id }}">
                        @include('widgets.area.controlAdvertising')
                    @else
                        <div class="widget @if(Advertising::TYPE_ADVERTISING == $item->type) type-a @endif">
                    @endif
                @else
                    <div class="widget @if(Advertising::TYPE_ADVERTISING == $item->type) type-a @endif">
                @endif
                    <div class="widget-body">
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
</div>