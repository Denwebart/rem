<div class="row">
    <div class="area area-content">
        @foreach($advertising as $item)
            <div class="advertising">
                @if(Auth::user()->isAdmin())
                    <div class="buttons pull-right" style="display: none">
                        <a href="{{ URL::route('admin.advertising.edit', ['id' => $item->id]) }}" class="btn btn-info btn-sm">
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