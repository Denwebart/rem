<div id="rating" class="rating pull-right">
    <div id="rate-votes">{{ $page->getRating() }}</div>
    <div id="rate-voters">(голосовавших: <span>{{ $page->voters }}</span>)</div>
    <div id="rate-stars">
        <div id="jRate"></div>
    </div>
    <div id="rate-message"></div>
</div>

@section('script')
    @parent

    {{ HTML::script('js/jRate.js') }}

    <script type="text/javascript">
        $("#jRate").jRate({
            rating: '<?php echo $page->getRating(); ?>',
            precision: 0, // целое число
            width: 30,
            height: 30,
            startColor: '#84BCE6',
            endColor: '#2D4C7F',
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
                data: {rating: rating},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $('#rate-votes').text(response.rating);
                        $('#rate-voters span').text(response.voters);
                        $('#rate-message').text(response.message);
                        $('#jRate').remove();
                        $('#rate-stars').append('<div id="jRate"></div>');
                        $("#jRate").jRate({
                            rating: response.rating,
                            precision: 0, // целое число
                            width: 30,
                            height: 30,
                            startColor: '#84BCE6',
                            endColor: '#2D4C7F',
                            onSet: function(rating) {
                                sendAjaxRating();
                            }
                        });
                    } else {
                        $('#rate-message').text(response.message);
                        $('#jRate').remove();
                        $('#rate-stars').append('<div id="jRate"></div>');
                        $("#jRate").jRate({
                            rating: response.rating,
                            precision: 0, // целое число
                            width: 30,
                            height: 30,
                            startColor: '#84BCE6',
                            endColor: '#2D4C7F',
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