@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li>
            <a href="{{ URL::to($page->parent->getUrl()) }}">
                {{ $page->parent->getTitle() }}
            </a>
        </li>
        <li>
            <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()]) }}">
                {{ $user->login }}
            </a>
        </li>
        <li>{{ $page->getTitle() }}</li>
    </ol>

    <section id="content" class="well">

        @if($page->title)
            <h2>{{ $page->title }}</h2>
        @endif

        {{ $areaWidget->contentTop() }}

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

            <ul class="tags">
                @foreach($page->tags as $tag)
                    <li>
                        <a href="{{ URL::route('journal.tag', ['journalAlias' => $journalAlias, 'tag' => $tag->title]) }}" title="{{ $tag->title }}">
                            {{ $tag->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{ $areaWidget->contentMiddle() }}

        {{-- Читайте также --}}
        <?php $relatedWidget = app('RelatedWidget') ?>
        {{ $relatedWidget->articles($page) }}
        {{ $relatedWidget->questions($page) }}

        {{-- Комментарии --}}
        <?php $commentWidget = app('CommentWidget'); ?>
        {{ $commentWidget->show($page) }}

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
