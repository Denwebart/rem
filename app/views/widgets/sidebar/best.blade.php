<div id="bests-sidebar-widget" class="sidebar-widget">
    <h4>TOP- 10 (рейтинг голосов)</h4>

    @foreach($pages as $page)
        <div class="item">
            <a href="{{ URL::to($page->getUrl()) }}">
                {{ $page->getTitle() }}
            </a>
            <div class="rate clearfix">
                <div class="rate-votes pull-left">{{ $page->getRating() }}</div>
                <div class="rate-stars pull-left">
                    <div id="jRate-{{ $page->id }}"></div>
                </div>
            </div>
            @section('script')
                @parent
                <script type="text/javascript">
                    $("#jRate-<?php echo $page->id; ?>").jRate({
                        rating: '<?php echo $page->getRating(); ?>',
                        readOnly: true,
                        startColor: '#84BCE6',
                        endColor: '#2D4C7F'
                    });
                </script>
            @endsection
        </div>
    @endforeach

</div>
@section('script')
    @parent

    {{ HTML::script('js/jRate.js') }}

@endsection