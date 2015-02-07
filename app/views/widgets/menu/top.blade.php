<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-top" aria-expanded="false" aria-controls="navbar-top">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar-top" class="collapse navbar-collapse">
            <ul class="nav navbar-nav pull-right">
                @foreach($pages as $page)
                    <li><a href="{{ URL::to($page->alias) }}">{{ $page->menu_title }}</a></li>
                @endforeach
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>