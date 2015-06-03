@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li>
            <a href="{{ URL::to($page->parent->parent->getUrl()) }}">
                {{ $page->parent->parent->getTitle() }}
            </a>
        </li>
        <li>
            <a href="{{ URL::to($page->parent->parent->getUrl() . '/' . $user->getLoginForUrl()) }}">
                {{ $user->login }}
            </a>
        </li>
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

            <ul class="tags">
                @foreach($page->tags as $tag)
                    <li>
                        <a href="{{ URL::route('search', ['tag' => $tag->title]) }}" title="{{ $tag->title }}">
                            {{ $tag->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Комментарии --}}
        <?php $commentWidget = app('CommentWidget'); ?>
        {{ $commentWidget->show($page) }}

    </section>
@stop
