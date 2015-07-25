<div id="latest-sidebar-widget" class="list-group sidebar-widget">
    @foreach($pages as $key => $page)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="date pull-left">
                <i class="icon mdi-action-event pull-left"></i>
                <span class="text pull-left">{{ DateHelper::dateFormat($page->published_at) }}</span>
            </div>
            <div class="clearfix"></div>
            <a href="{{ URL::to($page->getUrl()) }}">
                {{ $page->getTitle() }}
            </a>
        </div>
    @endforeach
</div>