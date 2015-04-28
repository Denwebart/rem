<section id="related-widget">
    <h3>Читайте также:</h3>

    <ul class="related-pages">

        @foreach($pages as $item)

            <li><a href="{{ URL::to($item->getUrl()) }}">{{ $item->title }}</a></li>

        @endforeach

    </ul>

</section> <!-- end of .comments-area -->


