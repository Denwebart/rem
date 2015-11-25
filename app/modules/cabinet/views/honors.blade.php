@extends('cabinet::layouts.honors')

@section('content')
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => [
        [
            'title' => 'Награды'
        ]
    ]])

    <section id="content" class="well" itemscope itemtype="http://schema.org/Article">

        <meta itemprop="datePublished" content="{{ DateHelper::dateFormatForSchema($page->published_at) }}">

        <div class="row">
            <div class="@if($page->showRating()) col-lg-9 col-md-12 col-sm-9 col-xs-12 @else col-lg-12 col-md-12 col-sm-12 col-xs-12 @endif">
                @if($page->title)
                    <h2 itemprop="headline">{{ $page->title }}</h2>
                @endif
            </div>
            @if($page->showRating())
                <div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
                    {{-- Рейтинг --}}
                    @include('widgets.rating')
                </div>
            @endif
        </div>

        @if(!$page->is_container)
            <div class="page-info">
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
                </div>
            </div>
        @endif

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
        @endif

        {{ $areaWidget->contentMiddle() }}

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
