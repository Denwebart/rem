@extends('layouts.main')

@section('breadcrumbs')
    <?php
        if($page->parent_id != 0) {
            if($page->parent) {
                if($page->parent->parent_id != 0) {
                    if($page->parent->parent) {
                        $breadcrumbs[0]['title'] = $page->parent->parent->getTitle();
                        $breadcrumbs[0]['url'] = URL::to($page->parent->parent->getUrl());
                    }
                }
                $breadcrumbs[1]['title'] = $page->parent->getTitle();
                $breadcrumbs[1]['url'] = URL::to($page->parent->getUrl());
            }
        }
        $breadcrumbs[2]['title'] = $page->getTitleForBreadcrumbs();
    ?>
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => $breadcrumbs])
@stop

@section('content')
	<section id="content" class="well" itemscope itemtype="http://schema.org/Article">

        <div class="row">
            <div class="@if($page->showRating()) col-lg-9 col-md-12 col-sm-9 col-xs-12 @else col-lg-12 col-md-12 col-sm-12 col-xs-12 @endif">
                @if($page->is_show_title)
                    <h2 itemprop="headline">{{ $page->title }}</h2>
                @endif
            </div>
            @if($page->showRating())
                <div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
                    {{-- Рейтинг --}}
                    @include('widgets.rating')

                    @if(!$page->is_container)
                        <div class="date pull-left hidden-lg hidden-md hidden-sm">
                            <i class="material-icons pull-left">today</i>
                            <span class="pull-left">{{ DateHelper::dateFormat($page->published_at) }}</span>
                        </div>
                    @endif

                </div>
            @endif
        </div>

        <div class="page-info">
            <div class="pull-left">
                @if($page->isLastLevel())
                    <div class="user pull-left" itemprop="author" itemscope itemtype="http://schema.org/Person">
                        <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}" itemprop="url">
                            {{ $page->user->getAvatar('mini', ['width' => '25', 'class' => 'pull-left']) }}
                            <span class="login pull-left hidden-xs" itemprop="name">{{ $page->user->login }}</span>
                        </a>
                    </div>
                @endif
                <div class="date pull-left hidden-xs">
                    <i class="material-icons">today</i>
                    <time datetime="{{ DateHelper::dateFormatForSchema($page->published_at) }}" itemprop="datePublished">
                        {{ DateHelper::dateFormat($page->published_at) }}
                    </time>
                </div>
            </div>
            <div class="pull-right">
                @if($page->showViews())
                    <div class="views pull-left" title="Количество просмотров" data-toggle="tooltip" data-placement="bottom">
                        <i class="material-icons">visibility</i>
                        <span>{{ $page->views }}</span>
                    </div>
                @endif

                @if($page->showComments())
                    <div class="comments-count pull-left" title="Количество комментариев" data-toggle="tooltip" data-placement="bottom">
                        <i class="material-icons">chat_bubble</i>
                        <a href="#comments">
                        <span class="count-comments" itemprop="commentCount">
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

        {{ $areaWidget->contentTop() }}

		@if($page->content)
			<div class="content" itemprop="articleBody">
                @if($page->image)
                    <a class="fancybox pull-left" data-fancybox-group="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage('origin', ['class' => 'page-image']) }}
                    </a>
                @endif
				{{ $page->getContentWithWidget() }}
			</div>

            <div class="clearfix"></div>
            @include('widgets.sidebar.socialButtons')
		@endif

		{{ $areaWidget->contentMiddle() }}

		@if($page->parent_id != 0)
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