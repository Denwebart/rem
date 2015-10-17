<div id="navbar-top">
    <ul class="nav navbar-nav">
        @foreach($items as $item)
            <li class="{{ Request::is($item->getUrl()) ? 'active' : '' }}">
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
</div><!--/.nav-collapse -->