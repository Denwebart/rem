<div class="navbar-top">
    <ul class="nav navbar-nav">
        @foreach($items as $item)
            <li class="{{ Request::is($item->getUrl()) ? 'active' : '' }}">
                <a href="{{ URL::to($item->getUrl()) }}">
                    {{ $item->getTitle() }}
                </a>
            </li>
        @endforeach
    </ul>
</div><!--/.nav-collapse -->