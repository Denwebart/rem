@extends('layouts.main')

@section('content')
	<section id="content" class="well">
        <div class="row">
            <div class="col-md-9">
                @if($page->title)
                    <h2>{{ $page->title }}</h2>
                @endif
            </div>
            <div class="col-md-3">
                @if($page->showRating())
                    {{-- Рейтинг --}}
                    @include('widgets.rating')
                @endif
            </div>
        </div>

        <div class="clearfix"></div>

		{{ $areaWidget->contentTop() }}

		@if($page->content)
			<div class="content">
                @if($page->image)
                    {{ $page->getImage() }}
                @endif
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