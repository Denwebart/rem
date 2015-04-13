<div id="latest-sidebar-widget" class="list-group sidebar-widget">
    <h4>Самое новое</h4>

    @foreach($pages as $page)
        <div class="list-group-item">
            <div class="row-picture">
                <div class="date">
                    <i class="mdi-action-event"></i>
                    <span class="day">{{ DateHelper::date('j', $page->published_at) }}</span>
                    <span class="month">{{ DateHelper::date('M', $page->published_at) }}</span>
                    <span class="year">{{ DateHelper::date('Y', $page->published_at) }}</span>
                </div>
            </div>
            <div class="row-content">
                <a href="{{ URL::to($page->getUrl()) }}">
                    {{ $page->getTitle() }}
                </a>
            </div>
        </div>
        <div class="list-group-separator"></div>

    @endforeach

</div>