<div id="bests-sidebar-widget" class="sidebar-widget">
    <h4>TOP- 10 (рейтинг голосов)</h4>

    @foreach($pages as $page)
        <div class="item">
            <a href="{{ URL::to($page->alias) }}">
                {{ $page->title }}
            </a>
            <div class="views">Оценка: {{ $page->getRating() }}</div>
        </div>
    @endforeach

</div>