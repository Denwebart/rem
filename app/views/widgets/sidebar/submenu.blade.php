@if(count($items))
    <div id="submenu-sidebar-widget" class="sidebar-widget">
        <h3>Разделы</h3>
        <ul>
            @foreach($items as $item)
                <li @if(Request::is($item->getUrl() . '*')) class="active" @endif>
                    <a href="{{ URL::to($item->getUrl()) }}">
                        <span>
                            @if($item->menu_title)
                                {{ $item->menu_title }}
                            @else
                                {{ $item->title }}
                            @endif
                        </span>
                        <small class="label label-info">
                            {{ $item->pagesCount }}
                        </small>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif