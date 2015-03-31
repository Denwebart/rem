@extends('layouts.main')

@section('content')
	<section id="content">
		@if($page->title)
			<h2>{{ $page->title }}</h2>
		@endif
		@if($page->content)

			<div class="content">

				@if($page->showRating())
					{{-- Рейтинг --}}

					<div id="rating">
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
										}
									}
								});
							}
						</script>
					@endsection
				@endif

				{{ $page->content }}
			</div>
		@endif
	</section>
@stop