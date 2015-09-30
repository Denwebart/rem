<div id="not-best-sidebar-widget" class="list-group sidebar-widget">
    @foreach($pages as $key => $page)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="rating pull-left">
                <span class="rate-voters pull-right">
                    <span class="text">Проголосовало:</span>
                    <span class="count">{{ $page->voters }}</span>
                </span>
                <div class="pull-left">
                    <span class="rate-votes pull-left">
                        {{ $page->getRating() }}
                    </span>
                    <div class="rate-stars pull-left">
                        <div id="jRate-{{ $page->id }}"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <a href="{{ URL::to($page->getUrl()) }}">
                {{ $page->getTitle() }}
            </a>
        </div>
        @section('ratingNotBest')
            @parent
            <script type="text/javascript">
                $("#jRate-<?php echo $page->id ?>").jRate({
                    rating: '<?php echo $page->getRating(); ?>',
                    precision: 0, // целое число
                    width: 20,
                    height: 20,
                    startColor: '#03A9F4',
                    endColor: '#004B7D',
                    readOnly: true
                });
            </script>
        @stop
    @endforeach
</div>

@section('script')
    @parent

    {{ HTML::script('js/jRate.js') }}

    @yield('ratingNotBest')
@stop