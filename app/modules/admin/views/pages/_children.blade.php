<ul class="children">
    @foreach($pages as $page)
        <li>
            <a href="{{ $page->getUrl() }}">
                {{ $page->getTitle() }}
            </a>
        </li>
    @endforeach
</ul>
