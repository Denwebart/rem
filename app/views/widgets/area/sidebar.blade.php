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
                        <div class="widget access-{{ $item->access }}{{ $item->is_active ? '' : ' not-active'}} @if(Advertising::TYPE_ADVERTISING == $item->type) type-a @endif" {{ $item->is_active ? '' : 'style="display: none"'}} data-widget-id="{{ $item->id }}">
                        <div class="widget-title" style="display: none">
                            <a href="{{ URL::route('admin.advertising.index', ['id' => $item->id]) }}" title="Смотреть в админке" data-toggle="tooltip">
                                {{ $item->title }}
                            </a>
                        </div>
                    @else
                        <div class="widget @if(Advertising::TYPE_ADVERTISING == $item->type) type-a @endif">
                    @endif
                @else
                    <div class="widget @if(Advertising::TYPE_ADVERTISING == $item->type) type-a @endif">
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
            </div>
        @endforeach
    </div>
</div>