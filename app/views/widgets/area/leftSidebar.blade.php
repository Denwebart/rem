@foreach($advertising as $item)
    <div class="alert alert-dismissable alert-info">
        @if($item->is_show_title)
            <h3>{{ $item->title }}</h3>
        @endif

        {{ $item->code }}

    </div>
@endforeach