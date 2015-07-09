<div class="row">
    <div class="area area-content">
        @foreach($advertising as $item)
            <div class="advertising">
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