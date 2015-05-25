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

        @if($page->title)
            <h2>{{ $page->title }}</h2>
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

            Автор: {{ $page->user->getFullName() }}

            {{ $page->content }}
        </div>

        @if(Auth::check())
            <!-- Подписка на вопрос ("Подписки") -->
            @include('widgets.subscribe')
        @endif

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
