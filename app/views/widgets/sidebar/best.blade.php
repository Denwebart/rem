<div id="best-sidebar-widget" class="list-group sidebar-widget">
    @foreach($pages as $key => $page)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="rating pull-left">
                <span class="rate-votes pull-left">
                    {{ $page->getRating() }}
                </span>
                <div class="rate-stars pull-left">
                    <div class="jRate"></div>
                </div>
                <span class="rate-voters pull-right">
                    <span class="text">Проголосовало:</span>
                    <span class="count">{{ $page->voters }}</span>
                </span>
            </div>
            <div class="clearfix"></div>
            <a href="{{ URL::to($page->getUrl()) }}">
                {{ $page->getTitle() }}
            </a>
        </div>
        @section('rating')
            <script type="text/javascript">
                $(".jRate").jRate({
                    rating: '<?php echo $page->getRating(); ?>',
                    precision: 0, // целое число
                    width: 20,
                    height: 20,
                    startColor: '#03A9F4',
                    endColor: '#004B7D',
                    readOnly: true
                });
            </script>
        @endsection
    @endforeach
</div>

@section('script')
    @parent

    {{ HTML::script('js/jRate.js') }}

    @yield('rating')
@endsection