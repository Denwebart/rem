<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {{ $menuWidget->bottomMenu() }}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="copyright">
                    <a href="{{ URL::to('/') }}">
                        {{ HTML::image('images/logo-circle-footer.png', '', [
                            'class' => 'img-responsive margin-bottom-20 logo',
                        ]) }}
                    </a>
                    <!-- Копирайт -->
                    @if(isset($settings['copyright']))
                        {{ $settings['copyright']['value'] }}
                        2010 - {{ \Carbon\Carbon::now()->year }}
                    @endif
                </div>
            </div>
        </div>
        <!-- Счетчик -->
        @if(isset($settings['counter']))
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="counter">
                        {{ $settings['counter']['value'] }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</footer>

@include('widgets.siteMessages.siteMessages')

<!-- Back To Top -->
<a href="javascript:void(0)" class="back-to-top">
    <i class="material-icons">keyboard_arrow_up</i>
</a>

@section('script')
    @parent

    <!-- Back To Top -->
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
@stop