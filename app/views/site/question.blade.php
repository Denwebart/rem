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

        @if(count($page->bestComments))
            <i class="mdi-action-done mdi-success" style="font-size: 20pt;"></i>
            <span class="text-success">Есть решение</span>
        @endif

        <div class="content">

            @if($page->showViews())
                Количество просмотров: {{ $page->views }}
            @endif

            {{-- Рейтинг --}}
            @include('widgets.rating')

            @if(Auth::check())
                <!-- Сохранение страницы в избранное ("Сохраненное") -->
                @include('widgets.savedPages')
            @endif

            Автор: {{ $page->user->login }} ({{ $page->user->getFullName() }})

            {{ $page->content }}
        </div>

        <!-- Подписка на вопрос ("Подписки") -->
        @include('widgets.subscribe')

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

    </section>
@stop
