<div class="row">
    <div class="area area-site">
        @foreach($advertising as $item)
            <div class="advertising">
                @if($item->is_show_title)
                    <h3>{{ $item->title }}</h3>
                @endif
                {{ $item->code }}
            </div>
        @endforeach
    </div>
</div>