@extends('layouts.main')

@section('content')
	<section id="content" class="well">
		@if($page->title)
			<h2>{{ $page->title }}</h2>
		@endif
		@if($page->content)

			<div class="content">

				@if($page->showRating())
					{{-- Рейтинг --}}
					@include('widgets.rating')
				@endif

				{{ $page->content }}

			</div>
		@endif

		<section id="blog-area">
			@foreach($articles as $article)
				<div class="row">
					<div class="col-md-12">
						<h3>
							<a href="{{ URL::to($article->getUrl()) }}">
								{{ $article->title }}
							</a>
						</h3>
					</div>
					<div class="col-md-5">
						<a href="{{ URL::to($article->getUrl()) }}">
							{{ HTML::image(Config::get('settings.defaultImage'), '', ['class' => 'img-responsive']) }}
						</a>
					</div>
					<div class="col-md-7">
						<div class="pull-right">
							@if($article->parent->parent)
								<a href="{{ URL::to($article->parent->parent->getUrl()) }}">
									{{ $article->parent->parent->getTitle() }}
								</a>
								/
							@endif
							<a href="{{ URL::to($article->parent->getUrl()) }}">
								{{ $article->parent->getTitle() }}
							</a>
						</div>
						<p>{{ $article->getIntrotext() }}</p>
						<a class="pull-right" href="#">Читать полностью <span class="glyphicon glyphicon-chevron-right"></span></a>
					</div>
				</div>
				<hr/>
			@endforeach
			<div>
				{{ $articles->links() }}
			</div>
		</section><!--blog-area-->
	</section>
@stop