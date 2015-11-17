@if(count($items))
    <div id="submenu-sidebar-widget" class="sidebar-widget">
        <h3>
            @if($page->type == Page::TYPE_QUESTIONS)
                Категории вопросов
            @else
                Категории статей
            @endif
        </h3>
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
                        @if($item->pagesCount)
                            <small class="label label-info">
                                {{ $item->pagesCount }}
                            </small>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif