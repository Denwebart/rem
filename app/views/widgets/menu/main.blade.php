<div class="container" style="box-sizing: content-box">
    <nav class="navbar navbar-custom">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main" aria-expanded="false" aria-controls="navbar-main">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar-main" class="navbar-collapse collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav">
                @foreach($pages as $page)
                    <li class="dropdown {{ (Request::is($page->alias . '/*') || Request::is($page->alias)) ? 'active' : '' }}">
                        <a href="{{ URL::to($page->getUrl()) }}">
                            {{ $page->getTitle() }}
                            @if($page->show_submenu && count($page->publishedChildren))
                                <b class="caret"></b>
                            @endif
                        </a>
                        @if($page->show_submenu && count($page->publishedChildren))
                            <ul class="dropdown-menu">
                                @foreach($page->publishedChildren as $child)
                                    <li><a href="{{ URL::to($child->getUrl()) }}">{{ $child->getTitle() }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div><!--/.nav-collapse -->
    </nav>
</div>