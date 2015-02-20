<div id="navbar-top" class="collapse navbar-collapse">
    <ul class="nav navbar-nav pull-right">
        @foreach($pages as $page)
            <li class="{{ (URL::current() != URL::to($page->alias)) ? '' : 'active' }}"><a href="{{ URL::to($page->alias) }}">{{ $page->getTitle() }}</a></li>
        @endforeach
    </ul>
</div><!--/.nav-collapse -->