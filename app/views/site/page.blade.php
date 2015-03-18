@extends('layouts.main')

@section('content')
	<section id="content">

		<ol class="breadcrumb">
			<li><a href="{{ URL::to('/') }}">Главная</a></li>
			@if($page->parent)
				<li>
					<a href="{{ URL::to($page->parent->alias) }}">
						{{ $page->parent->getTitle() }}
					</a>
				</li>
			@endif
			<li>{{ $page->getTitle() }}</li>
		</ol>

		@if($page->title)
			<h2>{{ $page->title }}</h2>
		@endif
		@if($page->content)
			<div class="content">

				@if($page->show_rating)
					{{-- Рейтинг --}}

					<div id="rating">

						<div id="rate-votes">{{ $page->getRating() }}</div>
						<div id="rate-voters">(голосовавших: <span>{{ $page->voters }}</span>)</div>
						<div id="jRate"></div>
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

	//							readOnly: true,

								// onSet, onChange
								onSet: function(rating) {
									$.ajax({
										url: '<?php echo URL::route('rating.stars', ['id' => $page->id]) ?>',
										dataType: "text json",
										type: "POST",
										data: {rating: rating},
										success: function(response) {
											if(response.success){
												$('#rate-votes').text(response.rating);
												$('#rate-voters span').text(response.voters);
												$('#rate-message').text(response.message);
											} else {
												$('#rate-message').text(response.message);
											}
										}
									});
								}
							});
						</script>

					@endsection
				@endif

				{{ $page->content }}
			</div>
		@endif

		@if($page->show_comments)
			{{-- Комментарии --}}
			<?php $commentWidget = app('CommentWidget') ?>
			{{ $commentWidget->show($page) }}
		@endif

	</section>
@stop