@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li class="home-page">
            <a href="{{ URL::to('/') }}">
                <i class="material-icons">home</i>
            </a>
        </li>
        @if($page->parent)
            <li>
                <a href="{{ URL::to($page->parent->parent->getUrl()) }}">
                    {{ $page->parent->parent->getTitle() }}
                </a>
            </li>
            <li>
                <a href="{{ URL::to($page->parent->getUrl()) }}">
                    {{ $page->parent->getTitle() }}
                </a>
            </li>
        @endif
        <li class="hidden-md hidden-xs">{{ $page->getTitleForBreadcrumbs() }}</li>
    </ol>

    <section id="content" class="well">

        <div class="row">
            <div class="@if($page->showRating()) col-lg-9 col-md-12 col-sm-9 col-xs-12 @else col-lg-12 col-md-12 col-sm-12 col-xs-12 @endif">
                <h2>
                    {{ $page->title }}
                    @if(count($page->bestComments))
                        <i class="material-icons mdi-success" title="Есть решение" style="font-size: 26px">done</i>
                    @endif
                </h2>
            </div>
            @if($page->showRating())
                <div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
                    {{-- Рейтинг --}}
                    @include('widgets.rating')

                    <div class="date pull-left hidden-lg hidden-md hidden-sm" title="Дата публикации">
                        <i class="material-icons pull-left">today</i>
                        <span class="pull-left">{{ DateHelper::dateFormat($page->published_at) }}</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="page-info">
            <div class="pull-left">
                <div class="user pull-left">
                    <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                        {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                        <span class="login pull-left hidden-xs">{{ $page->user->login }}</span>
                    </a>
                </div>
                <div class="date pull-left hidden-xs" title="Дата публикации">
                    <i class="material-icons">today</i>
                    <span>{{ DateHelper::dateFormat($page->published_at) }}</span>
                </div>
            </div>
            <div class="pull-right">
                <div class="views pull-left" title="Количество просмотров">
                    <i class="material-icons">visibility</i>
                    <span>{{ $page->views }}</span>
                </div>

                <div class="answers-count pull-left" title="Количество ответов">
                    <i class="material-icons">question_answer</i>
                    <a href="#answers">
                        <span class="count-comments">
                            {{ count($page->publishedAnswers) }}
                        </span>
                    </a>
                </div>

                <div class="subscribers pull-left" title="Количество подписавшихся на вопрос">
                    <i class="material-icons">local_library</i>
                    <span>{{ count($page->subscribers) }}</span>
                </div>

                <!-- Сохранение страницы в сохраненное -->
                @include('widgets.savedPages')

            </div>
        </div>

        {{ $areaWidget->contentTop() }}

        <div class="content">
            @if($page->image)
                <a class="fancybox" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                    {{ $page->getImage('origin') }}
                </a>
            @endif
            {{ $page->getContentWithWidget() }}
        </div>

        <!-- Подписка на вопрос ("Подписки") -->
        @include('widgets.subscribe', ['subscriptionObject' => $page, 'subscriptionField' => Subscription::FIELD_PAGE_ID])

        {{ $areaWidget->contentMiddle() }}

        {{-- Читайте также --}}
        <?php $relatedWidget = app('RelatedWidget') ?>
        {{ $relatedWidget->questions($page) }}
        {{ $relatedWidget->articles($page) }}

        <div id="answers">
            {{-- Комментарии --}}
            <?php
                $commentWidget = app('CommentWidget');
                $commentWidget->title = 'Ответы';
                $commentWidget->formTitle = 'Написать ответ';
                $commentWidget->successMessage = 'Спасибо за ответ!';
            ?>
            {{ $commentWidget->show($page) }}
        </div>

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
