@extends('layouts.main')

@section('content')
	<ol class="breadcrumb">
		<li><a href="{{ URL::to('/') }}">Главная</a></li>
		@if($page->parent)
			@if($page->parent->parent)
				<li>
					<a href="{{ URL::to($page->parent->parent->getUrl()) }}">
						{{ $page->parent->parent->getTitle() }}
					</a>
				</li>
			@endif
			<li>
				<a href="{{ URL::to($page->parent->getUrl()) }}">
					{{ $page->parent->getTitle() }}
				</a>
			</li>
		@endif
		<li>{{ $page->getTitleForBreadcrumbs() }}</li>
	</ol>

	<section id="content" class="well">

		@if($page->title)
			<h2>{{ $page->title }}</h2>
		@endif

		{{ $areaWidget->contentTop() }}

		@if($page->content)
			<div class="content">

                @if($page->showViews())
                    Количество просмотров: {{ $page->views }}
                @endif

				@if($page->showRating())
					{{-- Рейтинг --}}
					@include('widgets.rating')
				@endif

                @if(Auth::check() && $page->isLastLevel())
					<!-- Сохранение страницы в избранное ("Сохраненное") -->
					@include('widgets.savedPages')
                @endif

				{{ $page->content }}
			</div>
		@endif

		{{ $areaWidget->contentMiddle() }}

		@if(count($children))
			<section id="blog-area" class="blog">
                <div class="count">
                    Показано: <span>{{ $children->count() }}</span>.
                    Всего: <span>{{ $children->getTotal() }}</span>.
                </div>
				@foreach($children as $child)
                    @include('site.postInfo', ['article' => $child])
				@endforeach
				{{ $children->links() }}
			</section><!--blog-area-->
		@endif

		@if(!$page->is_container && !count($page->children) && $page->parent_id != 0)
			{{-- Читайте также --}}
			<?php $relatedWidget = app('RelatedWidget') ?>
			{{ $relatedWidget->show($page) }}
		@endif

		@if($page->showComments())
            <div id="comments">
                {{-- Комментарии --}}
                <?php $commentWidget = app('CommentWidget') ?>
                {{ $commentWidget->show($page) }}
            </div>
		@endif

		{{ $areaWidget->contentBottom() }}

	</section>
@stop
