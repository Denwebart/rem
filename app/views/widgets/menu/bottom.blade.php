<nav>
    <ul id="navbar-bottom">
        @foreach($items as $item)
            <li class="{{ (Request::is($item->page->alias . '/*') || Request::is($item->page->getUrl())) ? 'active' : '' }}">
                <a href="{{ URL::to($item->page->getUrl()) }}">
                    {{ $item->getTitle() }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>