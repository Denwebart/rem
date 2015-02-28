<nav class="navbar navbar-custom">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main" aria-expanded="false" aria-controls="navbar-main">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar-main" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                @foreach($pages as $page)
                    <li class="dropdown {{ Request::is($page->alias . '/*') ? 'active' : '' }}">
                        <a href="{{ URL::to($page->alias) }}">{{ $page->getTitle() }}</a>
                        @if($page->show_submenu && count($page->publishedChildren))
                        <ul class="dropdown-menu" role="menu">
                            @foreach($page->publishedChildren as $child)
                                <li><a href="{{ URL::to($page->alias . '/' . $child->alias) }}">{{ $child->getTitle() }}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>