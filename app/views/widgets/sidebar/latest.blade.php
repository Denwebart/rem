<div id="latest-sidebar-widget" class="list-group sidebar-widget">
    @foreach($pages as $key => $page)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="row-picture">
                <div class="date">
                    <i class="mdi-action-event"></i>
                    <span class="day">{{ DateHelper::date('j', $page->published_at) }}</span>
                    <span class="month">{{ DateHelper::date('M', $page->published_at) }}</span>
                    <span class="year">{{ DateHelper::date('Y', $page->published_at) }}</span>
                    <span class="time">{{ DateHelper::date('H:i', $page->published_at) }}</span>
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