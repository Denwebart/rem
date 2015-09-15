@extends('layouts.main')

@section('content')
	<ol class="breadcrumb">
        <li class="home-page">
            <a href="{{ URL::to('/') }}">
                <i class="material-icons">home</i>
            </a>
        </li>
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
            <li class="hidden-md hidden-xs">{{ $page->getTitleForBreadcrumbs() }}</li>
        @else
            <li>{{ $page->getTitleForBreadcrumbs() }}</li>
		@endif
	</ol>

	<section id="content" class="well">

        <div class="row">
            <div class="@if($page->showRating()) col-lg-9 col-md-12 col-sm-9 col-xs-12 @else col-lg-12 col-md-12 col-sm-12 col-xs-12 @endif">
                @if($page->title)
                    <h2>{{ $page->title }}</h2>
                @endif
            </div>
            @if($page->showRating())
                <div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
                    {{-- Рейтинг --}}
                    @include('widgets.rating')

                    @if(!$page->is_container)
                        <div class="date pull-left hidden-lg hidden-md hidden-sm" title="Дата публикации">
                            <i class="material-icons pull-left">today</i>
                            <span class="pull-left">{{ DateHelper::dateFormat($page->published_at) }}</span>
                        </div>
                    @endif

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
                                <span class="login pull-left hidden-xs">{{ $page->user->login }}</span>
                            </a>
                        </div>
                        <div class="date pull-left hidden-xs" title="Дата публикации" data-toggle="tooltip" data-placement="top">
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
                        {{ $page->getImage('origin') }}
                    </a>
                @endif
				{{ $page->getContentWithWidget() }}

                @if(!$page->is_container)
                    <div class="clearfix"></div>
                    @include('widgets.sidebar.socialButtons')
                @endif
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
