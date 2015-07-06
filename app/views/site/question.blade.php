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

        <h2>{{ $page->title }}</h2>

        <div class="page-info">
            <div class="answers pull-left" title="Количество ответов">
                <span class="mdi-communication-forum"></span>
                <a href="#answers">
                    {{ count($page->publishedAnswers) }}
                </a>
            </div>
            @if(count($page->bestComments))
                <div class="best-answers pull-left" title="Вопрос решен">
                    <i class="mdi-action-done mdi-success" style="font-size: 20pt;"></i>
                    <span class="text-success">Есть решение</span>
                </div>
            @endif

            <div class="user pull-left">
                <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                    {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                    <span class="login pull-left">{{ $page->user->login }}</span>
                </a>
            </div>
            <div class="date pull-left" title="Дата публикации">
                <span class="mdi-action-today"></span>
                {{ DateHelper::dateFormat($page->published_at) }}
            </div>
            <div class="views pull-left" title="Количество просмотров">
                <span class="mdi-action-visibility"></span>
                {{ $page->views }}
            </div>
            @if(Auth::check())
                <!-- Сохранение страницы в сохраненное -->
                @include('widgets.savedPages')
            @endif
            {{-- Рейтинг --}}
            @include('widgets.rating')
        </div>
        <div class="clearfix"></div>

        {{ $areaWidget->contentTop() }}

        <div class="content">
            {{ $page->content }}
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
