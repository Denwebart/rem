<div id="navbar-top" class="pull-left">
    <ul class="nav navbar-nav pull-left">
        @foreach($pages as $page)
            <li class="{{ Request::is($page->getUrl()) ? 'active' : '' }}">
                <a href="{{ URL::to($page->getUrl()) }}">
                    {{ $page->getTitle() }}
                </a>
            </li>
        @endforeach
    </ul>
</div><!--/.nav-collapse -->