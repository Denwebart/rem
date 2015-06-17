<nav class="navbar navbar-custom">
    <div id="navbar-bottom" class="navbar-collapse collapse navbar-responsive-collapse">
        <ul class="nav navbar-nav">
            @foreach($pages as $page)
                <li class="{{ (Request::is($page->alias . '/*') || Request::is($page->getUrl())) ? 'active' : '' }}">
                    <a href="{{ URL::to($page->getUrl()) }}">
                        {{ $page->getTitle() }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div><!--/.nav-collapse -->
</nav>