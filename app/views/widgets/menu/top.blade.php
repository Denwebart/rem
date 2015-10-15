<div id="navbar-top">
    <ul class="nav navbar-nav">
        @foreach($items as $item)
            <li class="{{ Request::is($item->page->getUrl()) ? 'active' : '' }}">
                <a href="{{ URL::to($item->page->getUrl()) }}">
                    {{ $item->getTitle() }}
                </a>
            </li>
        @endforeach
    </ul>
</div><!--/.nav-collapse -->