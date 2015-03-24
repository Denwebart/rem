<div id="unpopulars-sidebar-widget" class="sidebar-widget">
    <h4>Аутсайдеры</h4>

    @foreach($pages as $page)
        <div class="item">
            <a href="{{ URL::to($page->getUrl()) }}">
                {{ $page->getTitle() }}
            </a>
            <div class="views">Просмотры: {{ $page->views }}</div>
        </div>
    @endforeach

</div>