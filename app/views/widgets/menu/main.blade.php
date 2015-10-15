<div class="container" style="box-sizing: content-box">
    <nav class="navbar navbar-custom main-menu">
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
                @foreach($items as $item)
                    <li class="dropdown {{ (Request::is($item->page->alias . '/*') || Request::is($item->page->getUrl())) ? 'active' : '' }}">
                        <a href="{{ URL::to($item->page->getUrl()) }}">
                            {{ $item->getTitle() }}
                            @if(count($item->children))
                                <b class="caret hidden-xs"></b>
                            @endif
                        </a>
                        @if(count($item->children))
                            <ul class="dropdown-menu hidden-xs">
                                @foreach($item->children as $child)
                                    <li>
                                        <a href="{{ URL::to($child->page->getUrl()) }}">
                                            {{ $child->getTitle() }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div><!--/.nav-collapse -->
    </nav>
</div>