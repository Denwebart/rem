@if(count($pages))
    <section id="related-widget">
        <h3>Статьи по теме:</h3>

        <ul class="related-pages">
            @foreach($pages as $item)
                <li><a href="{{ URL::to($item->getUrl()) }}">{{ $item->title }}</a></li>
            @endforeach
        </ul>

    </section> <!-- end of .comments-area -->
@endif