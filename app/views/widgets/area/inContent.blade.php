@foreach($advertising as $item)
    <div class="row">
        <div class="area area-content" style="margin: 0">
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
        </div>
    </div>
@endforeach