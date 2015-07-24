<div id="unpopular-sidebar-widget" class="list-group sidebar-widget">
    @foreach($pages as $key => $page)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="row-picture">
                <a href="{{ URL::to($page->getUrl()) }}">
                    <img class="square" src="/images/mini_default-image.jpg" alt="icon">
                </a>
                <div class="views">
                    <i class="mdi-image-remove-red-eye"></i>
                    <span>{{ $page->views }}</span>
                </div>
            </div>
            <div class="row-content">
                <p class="list-group-item-text">
                    <a href="{{ URL::to($page->getUrl()) }}">
                        {{ $page->getTitle() }}
                    </a>
                </p>
            </div>
        </div>
    @endforeach
</div>