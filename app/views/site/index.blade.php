@extends('layouts.main')

@section('content')
	<section id="content" class="well">
		@if($page->title)
			<h2>{{ $page->title }}</h2>
		@endif

        <div class="page-info">
            @if($page->showRating())
                {{-- Рейтинг --}}
                @include('widgets.rating')
            @endif
        </div>
        <div class="clearfix"></div>

		{{ $areaWidget->contentTop() }}

		@if($page->content)
			<div class="content">
				{{ $page->getContentWithWidget() }}
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