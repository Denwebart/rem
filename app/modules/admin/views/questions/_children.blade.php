<ul class="children" style="display:none;">
    @foreach($pages as $page)
        <li class="{{ !$page->is_published ? 'not-published' : ''}}">
            @if($page->is_container && count($page->children))
                <a href="javascript:void(0)" class="open" data-page-id="{{ $page->id }}">
                    <i class="fa fa-folder" style="color: #F0AD4E; font-size: 18px"></i>
                </a>
                <a href="{{ URL::route('admin.pages.children', ['id' => $page->id]) }}" class="title">
                    {{ $page->getTitle() }}
                </a>
            @else
                <i class="fa fa-file-text-o" style="color: #293C4E"></i>
                <span class="title">
                    {{ $page->getTitle() }}
                </span>
            @endif
            <a href="{{ URL::route('admin.pages.edit', ['id' => $page->id]) }}" class="label pull-right">
                <i class="fa fa-edit"></i>
            </a>

        </li>
    @endforeach
</ul>
