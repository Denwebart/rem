<div id="lasts-sidebar-widget" class="sidebar-widget">
    <h4>Самое новое</h4>

    @foreach($pages as $page)
        <div class="item">
            <div class="published-date">{{ DateHelper::dateFormat($page->published_at) }}</div>
            <a href="{{ URL::to($page->alias) }}">
                {{ $page->title }}
            </a>
        </div>
    @endforeach

</div>