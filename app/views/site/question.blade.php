@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
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
        <li>{{ $page->getTitle() }}</li>
    </ol>

    <section id="content" class="well">

        <div class="row">
            <div class="col-md-9">
                <h2>
                    @if(count($page->bestComments))
                        <i class="mdi-action-done mdi-success" style="font-size: 26px;" title="Вопрос решен"></i>
                    @endif
                    {{ $page->title }}
                </h2>
            </div>
            <div class="col-md-3">
                @if($page->showRating())
                    {{-- Рейтинг --}}
                    @include('widgets.rating')
                @endif
            </div>
        </div>

        <div class="page-info">
            <div class="pull-left">
                <div class="user pull-left">
                    <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                        {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                        <span class="login pull-left">{{ $page->user->login }}</span>
                    </a>
                </div>
                <div class="date pull-left" title="Дата публикации">
                    <span class="icon mdi-action-today"></span>
                    <span>{{ DateHelper::dateFormat($page->published_at) }}</span>
                </div>
            </div>
            <div class="pull-right">
                <div class="answers-count pull-left" title="Количество ответов">
                    <span class="icon mdi-communication-forum"></span>
                    <a href="#answers">
                    <span class="count-comments">
                        {{ count($page->publishedAnswers) }}
                    </span>
                    </a>
                </div>

                <div class="views pull-left" title="Количество просмотров">
                    <span class="icon mdi-action-visibility"></span>
                    <span>{{ $page->views }}</span>
                </div>

                <!-- Сохранение страницы в сохраненное -->
                @include('widgets.savedPages')
            </div>
        </div>

        {{ $areaWidget->contentTop() }}

        <div class="content">
            @if($page->image)
                <a class="fancybox" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                    {{ $page->getImage() }}
                </a>
            @endif
            {{ $page->getContentWithWidget() }}
        </div>

        <!-- Подписка на вопрос ("Подписки") -->
        @include('widgets.subscribe')

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
