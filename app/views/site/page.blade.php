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

        <div class="row">
            <div class="@if($page->showRating()) col-md-9 @else col-md-12 @endif">
                @if($page->title)
                    <h2>{{ $page->title }}</h2>
                @endif
            </div>
            @if($page->showRating())
                <div class="col-md-3">
                    {{-- Рейтинг --}}
                    @include('widgets.rating')
                </div>
            @endif
        </div>

        @if(!$page->is_container)
            <div class="page-info">
                <div class="pull-left">
                    @if($page->isLastLevel())
                        <div class="user pull-left">
                            <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                                {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                                <span class="login pull-left">{{ $page->user->login }}</span>
                            </a>
                        </div>
                        <div class="date pull-left" title="Дата публикации">
                            <i class="material-icons">today</i>
                            <span>{{ DateHelper::dateFormat($page->published_at) }}</span>
                        </div>
                    @endif
                </div>
                <div class="pull-right">
                    @if($page->showViews())
                        <div class="views pull-left" title="Количество просмотров">
                            <i class="material-icons">visibility</i>
                            <span>{{ $page->views }}</span>
                        </div>
                    @endif

                    @if($page->showComments())
                        <div class="comments-count pull-left" title="Количество комментариев">
                            <i class="material-icons">chat_bubble</i>
                            <a href="#comments">
                            <span class="count-comments">
                                {{ count($page->publishedComments) }}
                            </span>
                            </a>
                        </div>
                    @endif
                    @if($page->isLastLevel())
                        <!-- Сохранение страницы в сохраненное -->
                        @include('widgets.savedPages')
                    @endif
                </div>
            </div>
        @endif

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

		@if(count($children))
			<section id="blog-area" class="blog margin-top-10">
                <div class="count">
                    Показано: <span>{{ $children->count() }}</span>.
                    Всего: <span>{{ $children->getTotal() }}</span>.
                </div>
				@foreach($children as $key => $child)
                    @if(0 != $key)
                        <hr/>
                    @endif
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
