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
            {{ $user->login }}
        </li>
    </ol>

    <section id="content" class="well">

        @if($page->title)
            <h2>{{ $page->title }}</h2>
        @endif

        {{ $areaWidget->contentTop() }}

        <div class="content row journal-user-info">
            <div class="col-md-5">
                <a href="{{ URL::route('user.profile', ['ligin' => $user->getLoginForUrl()]) }}" class="avatar">
                    {{ $user->getAvatar(null, ['width' => '120px']) }}
                </a>
                <div class="honors">
                    @foreach($user->honors as $honor)
                        <a href="{{ URL::route('honor.info', ['alias' => $honor->alias]) }}" class="pull-left">
                            {{ $honor->getImage(null, ['width' => '25px', 'title' => $honor->title, 'alt' => $honor->title]) }}
                        </a>
                    @endforeach
                </div>
                @if(Auth::check())
                    @if(!Auth::user()->is($user))
                        <div class="buttons">
                            <a href="{{ URL::route('user.dialog', ['login' => Auth::user()->getLoginForUrl(), 'companion' => $user->getLoginForUrl()]) }}" class="btn btn-primary" title="Написать личное сообщение пользователю {{ $user->login }}">
                                Написать сообщение
                            </a>
                        </div>
                    @endif
                @endif
            </div>
            <div class="col-md-7">
                <a href="{{ URL::route('user.profile', ['ligin' => $user->getLoginForUrl()]) }}" class="login">
                    {{ $user->login }} ({{ $user->getFullName() }})
                </a>
                <ul class="info">
                    <li>Статей:
                        <a href="{{ URL::route('user.journal', ['login' => $user->getLoginForUrl()]) }}">
                            {{ count($user->publishedArticles) }}
                        </a>
                    </li>
                    <li>
                        Вопросов:
                        <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}">
                            {{ count($user->publishedQuestions) }}
                        </a>
                    </li>
                    <li>
                        Ответов:
                        <a href="{{ URL::route('user.answers', ['login' => $user->getLoginForUrl()]) }}">
                            {{ count($user->publishedAnswers) }}
                        </a>
                    </li>
                    <li>
                        Комментариев:
                        <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}">
                            {{ count($user->publishedComments) }}
                        </a>
                    </li>
                </ul>
                @if($user->is_banned)
                    <div class="banned">
                        {{ HTML::image(Config::get('settings.bannedImage'),
                        'Забанен ' . DateHelper::dateFormat($user->latestBanNotification->ban_at) . '. Причина бана: "' . $user->latestBanNotification->message . '"',
                        [
                            'class' => 'img-responsive',
                            'title' => 'Забанен ' . DateHelper::dateFormat($user->latestBanNotification->ban_at) . '. Причина бана: "' . $user->latestBanNotification->message . '"'
                        ]) }}
                    </div>
                @endif
            </div>
        </div>

        {{ $areaWidget->contentMiddle() }}

        @if(Auth::check())
            @if(Auth::user()->is($user))
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ URL::route('user.journal.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                            Написать статью
                        </a>
                    </div>
                </div>
            @endif
        @endif

        @if(count($articles))
            <section id="blog-area">
                <div class="count">
                    Показано статей: {{ $articles->count() }}. Всего: {{ $articles->getTotal() }}.
                </div>
                @foreach($articles as $article)
                    <div class="row">
                        <div class="col-md-12">
                            <h3>
                                <a href="{{ URL::to($article->getUrl()) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-right">
                                @if(Auth::check())
                                    @if($article->user_id == Auth::user()->id)
                                        <a href="{{ URL::route('user.journal.edit', ['login' => Auth::user()->getLoginForUrl(),'id' => $article->id]) }}" class="btn btn-info">
                                            Редактировать
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <p>{{ $article->getIntrotext() }}</p>

                            <ul class="tags">
                                @foreach($article->tags as $tag)
                                    <li>
                                        <a href="{{ URL::route('journal.tag', ['journalAlias' => $journalAlias, 'tag' => $tag->title]) }}" title="{{ $tag->title }}">
                                            {{ $tag->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <a class="pull-right" href="{{ URL::to($article->getUrl()) }}">
                                Читать полностью <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>
                @endforeach
                <div>
                    {{ $articles->links() }}
                </div>
            </section><!--blog-area-->
        @endif

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
