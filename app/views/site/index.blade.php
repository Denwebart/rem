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
                    <a class="fancybox" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage() }}
                    </a>
                @endif
				{{ $page->getContentWithWidget() }}
			</div>
		@endif

		{{ $areaWidget->contentMiddle() }}

		<section id="blog-area" class="blog margin-top-10">
            <div class="count">
                Показано: <span>{{ $articles->count() }}</span>.
                Всего: <span>{{ $articles->getTotal() }}</span>.
            </div>
			@foreach($articles as $key => $article)
                @if(0 != $key)
                    <hr/>
                @endif
				@include('site.postInfo')
			@endforeach
			{{ $articles->links() }}
		</section><!--blog-area-->

		{{ $areaWidget->contentBottom() }}
	</section>
@stop