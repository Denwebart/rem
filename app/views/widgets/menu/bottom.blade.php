<nav>
    <ul id="navbar-bottom">
        @foreach($items as $item)
            <li class="{{ (Request::is($item->alias . '/*') || Request::is($item->getUrl())) ? 'active' : '' }}">
                <a href="{{ URL::to($item->getUrl()) }}">
                    {{ $item->getTitle() }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>