<nav>
    <ul id="navbar-bottom">
        @foreach($pages as $page)
            <li class="{{ (Request::is($page->alias . '/*') || Request::is($page->getUrl())) ? 'active' : '' }}">
                <a href="{{ URL::to($page->getUrl()) }}">
                    {{ $page->getTitle() }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>