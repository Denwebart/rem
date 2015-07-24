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

        <div class="row">
            <div class="col-md-9">
                <h2>{{ $page->title }}</h2>
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
                <div class="views pull-left" title="Количество просмотров">
                    <span class="icon mdi-action-visibility"></span>
                    <span>{{ $page->views }}</span>
                </div>
                <div class="comments-count pull-left" title="Количество комментариев">
                    <span class="mdi-communication-messenger"></span>
                    <a href="#comments">
                    <span class="count-comments">
                        {{ count($page->publishedComments) }}
                    </span>
                    </a>
                </div>
                <!-- Сохранение страницы в сохраненное -->
                @include('widgets.savedPages')
            </div>
        </div>

        {{ $areaWidget->contentTop() }}

        <div class="content">
            @if($page->image)
                {{ $page->getImage() }}
            @endif
            {{ $page->getContentWithWidget() }}

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
        <div id="comments">
            <?php $commentWidget = app('CommentWidget'); ?>
            {{ $commentWidget->show($page) }}
        </div>

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
