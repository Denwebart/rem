<div id="navbar-bottom" class="collapse navbar-collapse">
    <ul class="nav navbar-nav">
        @foreach($pages as $page)
            <li><a href="{{ URL::to($page->alias) }}">{{ $page->menu_title }}</a></li>
        @endforeach
    </ul>
</div><!--/.nav-collapse -->
