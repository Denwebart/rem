<div id="popular-sidebar-widget" class="list-group sidebar-widget">
    @foreach($pages as $key => $page)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="row-picture">
                <a href="{{ URL::to($page->getUrl()) }}">
                    {{ $page->getImage('mini', ['class' => 'square'], false) }}
                </a>
            </div>
            <div class="row-content">
                <div class="views pull-right" title="Количество просмотров" data-toggle="tooltip" data-placement="top">
                    <i class="material-icons pull-left">visibility</i>
                    <span class="count pull-left">{{ $page->views }}</span>
                </div>
                <div class="clearfix"></div>
                <p class="list-group-item-text">
                    <a href="{{ URL::to($page->getUrl()) }}">
                        {{ $page->getTitle() }}
                    </a>
                </p>
            </div>
        </div>
    @endforeach
</div>