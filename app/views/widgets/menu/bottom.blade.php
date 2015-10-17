<nav>
    <ul id="navbar-bottom">
        @foreach($items as $item)
            <li class="{{ (Request::is($item->alias . '/*') || Request::is($item->getUrl())) ? 'active' : '' }}">
                <a href="{{ URL::to($item->getUrl()) }}">
                    @if($item->menu_title)
                        {{ $item->menu_title }}
                    @else
                        {{ $item->title }}
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</nav>