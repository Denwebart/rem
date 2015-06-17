<div id="navbar-top" class="collapse navbar-collapse">
    <ul class="nav navbar-nav pull-right">
        @foreach($pages as $page)
            <li class="{{ Request::is($page->getUrl()) ? 'active' : '' }}">
                <a href="{{ URL::to($page->getUrl()) }}">
                    {{ $page->getTitle() }}
                </a>
            </li>
        @endforeach
    </ul>
</div><!--/.nav-collapse -->