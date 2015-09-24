<ul class="children" style="display:none;">
    @foreach($pages as $page)
        <li class="{{ ($page->is_container) ? 'category' : 'page' }}{{ !$page->is_published ? ' not-published' : '' }}{{ isset($parentPage) ? (($parentPage->id == $page->id) ? ' curent' : '') : '' }}">
            @if($page->is_container)
                <a href="{{ URL::route('admin.pages.children', ['id' => $page->id]) }}" class="open" data-page-id="{{ $page->id }}">
                    <i class="fa fa-folder"></i>
                </a>
                <a href="{{ URL::route('admin.pages.children', ['id' => $page->id]) }}" class="title">
                    {{ $page->getTitle() }}
                    <span class="count">
                        ({{ count($page->children) }})
                    </span>
                </a>
            @else
                <i class="fa fa-file-text-o"></i>
                <span class="title">
                    {{ $page->getTitle() }}
                </span>
            @endif
            {{--<a href="{{ URL::route('admin.pages.edit', ['id' => $page->id]) }}" class="edit pull-right">--}}
                {{--<i class="fa fa-edit"></i>--}}
            {{--</a>--}}
        </li>
    @endforeach
</ul>