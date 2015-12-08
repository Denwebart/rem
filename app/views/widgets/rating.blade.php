<div id="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
    <div class="pull-right">
        <div class="rating">
            <div id="rate-votes" itemprop="ratingValue">{{ $page->getRating() }}</div>
            <meta itemprop="worstRating" content="0" />
            <meta itemprop="bestRating" content="5" />
            <meta itemprop="ratingCount" content="{{ $page->votes }}" />
            <div id="rate-voters" title="Количество проголосовавших" data-toggle="tooltip">
                (<i class="material-icons">group</i> <span itemprop="reviewCount">{{ $page->voters }}</span>)
            </div>
        </div>
        <div id="rate-stars" title="Голосовать" data-toggle="tooltip" data-placement="bottom">
            <div id="jRate"></div>
        </div>
    </div>
</div>

@section('script')
    @parent

    {{ HTML::script('js/jRate.min.js') }}

    <script type="text/javascript">
        $("#jRate").jRate({
            rating: '<?php echo $page->getRating(); ?>',
            precision: 0, // целое число
            width: 25,
            height: 25,
            shapeGap: '5px',
            startColor: '#03A9F4',
            endColor: '#004B7D',
            //readOnly: "<?php //echo Auth::check() ? (!Auth::user()->is($page->user) ? 1 : 0) : 1 ?>",
            // onSet, onChange
            onSet: function(rating) {
                sendAjaxRating(rating);
            }
        });

        function sendAjaxRating(rating) {
            return $.ajax({
                url: '<?php echo URL::route('rating.stars', ['id' => $page->id]) ?>',
                dataType: "text json",
                type: "POST",
                data: {rating: rating, 'userLogin': '<?php echo Auth::check() ? Auth::user()->login : '' ?>'},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        // всплывающее сообщение
                        $('#site-messages').prepend(response.message);
                        setTimeout(function() {
                            hideSiteMessage($('.site-message'));
                        }, 2000);

                        $('#rate-votes').text(response.rating);
                        $('#rate-voters span').text(response.voters);
                        $('#jRate').remove();
                        $('#rate-stars').append('<div id="jRate"></div>');
                        $("#jRate").jRate({
                            rating: response.rating,
                            precision: 0, // целое число
                            width: 25,
                            height: 25,
                            shapeGap: '5px',
                            startColor: '#03A9F4',
                            endColor: '#004B7D',
                            onSet: function(rating) {
                                sendAjaxRating();
                            }
                        });
                    } else {
                        // всплывающее сообщение
                        $('#site-messages').prepend(response.message);
                        setTimeout(function() {
                            hideSiteMessage($('.site-message'));
                        }, 2000);

                        $('#jRate').remove();
                        $('#rate-stars').append('<div id="jRate"></div>');
                        $("#jRate").jRate({
                            rating: response.rating,
                            precision: 0, // целое число
                            width: 25,
                            height: 25,
                            shapeGap: '5px',
                            startColor: '#03A9F4',
                            endColor: '#004B7D',
                            onSet: function(rating) {
                                sendAjaxRating();
                            }
                        });
                    }
                }
            });
        }
    </script>
@stop