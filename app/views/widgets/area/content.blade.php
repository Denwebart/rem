<div class="row">
    <div class="area area-content">
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
                        <h3>{{ $item->title }}</h3>
                    @endif
                    {{ $item->code }}
                </div>
            </div>
        @endforeach
    </div>
</div>