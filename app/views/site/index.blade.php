@extends('layouts.main')

@section('content')
	<section id="content" class="well">
		@if($page->title)
			<h2>{{ $page->title }}</h2>
		@endif

		{{ $areaWidget->contentTop() }}

		@if($page->content)

			<div class="content">

				@if($page->showRating())
					{{-- Рейтинг --}}
					@include('widgets.rating')
				@endif

				{{ $page->content }}

			</div>
		@endif

		{{ $areaWidget->contentMiddle() }}

		<section id="blog-area" class="blog">
            <div class="count">
                Показано: <span>{{ $articles->count() }}</span>.
                Всего: <span>{{ $articles->getTotal() }}</span>.
            </div>
			@foreach($articles as $article)
				@include('site.postInfo')
			@endforeach
			{{ $articles->links() }}
		</section><!--blog-area-->

		{{ $areaWidget->contentBottom() }}
	</section>
@stop