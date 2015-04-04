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
                                            $('#jRate').remove();
                                            $('#rate-stars').append('<div id="jRate"></div>');
                                            $("#jRate").jRate({
                                                rating: "<?php echo $page->getRating(); ?>",
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
				@endif

				{{ $page->content }}
			</div>
		@endif

		@if(count($page->publishedChildren))
			<section id="blog-area">
				@foreach($page->publishedChildren()->orderBy('published_at', 'DESC')->get() as $child)
					<div class="row">
						<div class="col-md-12">
							<h3>
								<a href="{{ URL::to($child->getUrl()) }}">
									{{ $child->title }}
								</a>
							</h3>
						</div>
						<div class="col-md-5">
							<a href="{{ URL::to($child->getUrl()) }}">
								{{ HTML::image(Config::get('settings.defaultImage'), '', ['class' => 'img-responsive']) }}
							</a>
						</div>
						<div class="col-md-7">
							<p>{{ $child->getIntrotext() }}</p>
							<a class="pull-right" href="#">Читать полностью <span class="glyphicon glyphicon-chevron-right"></span></a>
						</div>
					</div>
					<hr/>
				@endforeach
			</section><!--blog-area-->
		@endif

		@if($page->showComments())
			{{-- Комментарии --}}
			<?php $commentWidget = app('CommentWidget') ?>
			{{ $commentWidget->show($page) }}
		@endif

	</section>
@stop
