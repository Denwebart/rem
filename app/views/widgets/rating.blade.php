<div id="rating" class="rating pull-right">
    <div id="rate-votes">{{ $page->getRating() }}</div>
    <div id="rate-voters">(голосовавших: <span>{{ $page->voters }}</span>)</div>
    <div id="rate-stars">
        <div id="jRate"></div>
    </div>
</div>

@section('script')
    @parent

    {{ HTML::script('js/jRate.js') }}

    <script type="text/javascript">
        $("#jRate").jRate({
            rating: '<?php echo $page->getRating(); ?>',
            precision: 0, // целое число
            width: 25,
            height: 25,
            startColor: '#79AEDB',
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

                        $('#rate-votes').text(response.rating);
                        $('#rate-voters span').text(response.voters);
                        $('#jRate').remove();
                        $('#rate-stars').append('<div id="jRate"></div>');
                        $("#jRate").jRate({
                            rating: response.rating,
                            precision: 0, // целое число
                            width: 25,
                            height: 25,
                            startColor: '#79AEDB',
                            endColor: '#004B7D',
                            onSet: function(rating) {
                                sendAjaxRating();
                            }
                        });
                    } else {
                        // всплывающее сообщение
                        $('#site-messages').prepend(response.message);

                        $('#jRate').remove();
                        $('#rate-stars').append('<div id="jRate"></div>');
                        $("#jRate").jRate({
                            rating: response.rating,
                            precision: 0, // целое число
                            width: 25,
                            height: 25,
                            startColor: '#79AEDB',
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
@endsection