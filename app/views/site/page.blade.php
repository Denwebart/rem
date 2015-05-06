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

		@if(count($children))
			<section id="blog-area">
				@foreach($children as $child)
					<div class="row item">
						<div class="col-md-12">
							<h3>
								<a href="{{ URL::to($child->getUrl()) }}">
									{{ $child->title }}
								</a>
							</h3>
						</div>
						<div class="col-md-12">
							<div class="pull-right">
								@if($child->parent)
									@if($child->parent->parent)
										<a href="{{ URL::to($child->parent->parent->getUrl()) }}">
											{{ $child->parent->parent->getTitle() }}
										</a>
										/
									@endif
									<a href="{{ URL::to($child->parent->getUrl()) }}">
										{{ $child->parent->getTitle() }}
									</a>
								@endif
							</div>
							<div class="clearfix"></div>
							<a href="{{ URL::to($child->getUrl()) }}" class="image">
								{{ HTML::image(Config::get('settings.defaultImage'), '', ['class' => 'img-responsive']) }}
							</a>
							<p>{{ $child->getIntrotext() }}</p>
							<a class="pull-right" href="#">Читать полностью <span class="glyphicon glyphicon-chevron-right"></span></a>
						</div>
					</div>
					<hr/>
				@endforeach
				<div>
					{{ $children->links() }}
				</div>
			</section><!--blog-area-->
		@endif

		@if(!$page->is_container && !count($page->children) && $page->parent_id != 0)
			{{-- Читайте также --}}
			<?php $relatedWidget = app('RelatedWidget') ?>
			{{ $relatedWidget->show($page) }}
		@endif

		@if($page->showComments())
			{{-- Комментарии --}}
			<?php $commentWidget = app('CommentWidget') ?>
			{{ $commentWidget->show($page) }}
		@endif

	</section>
@stop
