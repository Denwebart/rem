@extends('cabinet::layouts.honors')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li>Награды</li>
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
