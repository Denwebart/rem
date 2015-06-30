<div class="row">
    <div class="area area-sidebar">
        @foreach($advertising as $item)
            <div class="advertising{{ $item->is_active ? '' : ' not-active'}}" {{ $item->is_active ? '' : 'style="display: none"'}}>
                @if(Auth::user()->isAdmin())
                    <div class="buttons pull-right" style="display: none">
                        <div class="access">
                            Доступно {{ Advertising::$access[$item->access] }}
                        </div>
                        <a href="{{ URL::route('admin.advertising.edit', ['id' => $item->id, 'backUrl' => urlencode(Request::url())]) }}" class="btn btn-info btn-sm">
                            <span class="mdi-editor-mode-edit"></span>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-warning btn-sm">
                            <span class="mdi-action-visibility-off"></span>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                @endif
                <div class="advertising-body">
                    @if($item->is_show_title)
                        <h3>{{ $item->title }}</h3>
                    @endif
                    {{ $item->code }}
                </div>
            </div>
        @endforeach
    </div>
</div>