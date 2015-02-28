<nav class="navbar navbar-custom">
    <div id="navbar-bottom" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            @foreach($pages as $page)
                <li class="{{ (URL::current() != URL::to($page->alias)) ? '' : 'active' }}"><a href="{{ URL::to($page->alias) }}">{{ $page->getTitle() }}</a></li>
            @endforeach
        </ul>
    </div><!--/.nav-collapse -->
</nav>