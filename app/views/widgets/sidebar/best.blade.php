<div id="best-sidebar-widget" class="list-group sidebar-widget">
    @foreach($pages as $key => $page)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="row-picture">
                <i class="mdi-action-grade"></i>
                <div class="rate">
                    <div class="rate-votes">{{ $page->getRating() }}</div>
                    <div class="rate-voters">
                        ({{ $page->voters }})
                    </div>
                </div>
            </div>
            <div class="row-content">
                <a href="{{ URL::to($page->getUrl()) }}">
                    {{ $page->getTitle() }}
                </a>
            </div>
        </div>
    @endforeach
</div>