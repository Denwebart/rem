<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {{ $menuWidget->bottomMenu() }}
            </div>

            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>

            <div class="col-md-12">
                <!-- Копирайт -->
                @if(isset($settings))
                    <div class="copyright">
                        {{ $settings['copyright']['value'] }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</footer>

@include('widgets.siteMessages.siteMessages')

<!-- Back To Top -->
<a href="javascript:void(0)" class="back-to-top">
    <i class="material-icons">keyboard_arrow_up</i>
</a>

@section('script')
    @parent

    <script type="text/javascript">
        $(document).ready(function($){
            // browser window scroll (in pixels) after which the "back to top" link is shown
            var offset = 300,
            //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
                    offset_opacity = 1200,
            //duration of the top scrolling animation (in ms)
                    scroll_top_duration = 500,
            //grab the "back to top" link
                    $back_to_top = $('.back-to-top');

            //hide or show the "back to top" link
            $(window).scroll(function(){
                ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('is-visible') : $back_to_top.removeClass('is-visible fade-out');
                if( $(this).scrollTop() > offset_opacity ) {
                    $back_to_top.addClass('fade-out');
                }
            });

            //smooth scroll to top
            $back_to_top.on('click', function(event){
                event.preventDefault();
                $('body,html').animate({
                            scrollTop: 0
                        }, scroll_top_duration
                );
            });

        });
    </script>
@endsection