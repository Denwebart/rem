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
                    <li>
                        Статей:
                        <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()]) }}">
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
                @if(!Ip::isBanned())
                    @if(!$user->is_banned)
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ URL::route('user.journal.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                                    Написать статью
                                </a>
                            </div>
                        </div>
                    @else
                        @include('cabinet::user.banMessage')
                    @endif
                @else
                    @include('messages.bannedIp')
                @endif
            @endif
        @endif

        @if(count($articles))
            <section id="blog-area" class="blog">
                <div class="count">
                    Показано статей: <span>{{ $articles->count() }}</span>.
                    Всего: <span>{{ $articles->getTotal() }}</span>.
                </div>
                @foreach($articles as $article)
                    <div class="row" data-article-id="{{ $article->id }}">
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
                                    @if((Auth::user()->is($user) && !IP::isBanned() && !$user->is_banned) || Auth::user()->isAdmin())
                                        <div class="pull-right">
                                            <a href="{{ URL::route('user.journal.edit', ['login' => $user->getLoginForUrl(),'id' => $article->id]) }}" class="btn btn-info">
                                                Редактировать
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-danger delete-article" data-id="{{ $article->id }}">
                                                Удалить
                                            </a>
                                            <div class="status">
                                                Статус:
                                                {{ ($article->is_published) ? 'Опубликована' : 'Неопубликована' }}
                                            </div>
                                        </div>
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
                {{ $articles->links() }}
            </section><!--blog-area-->
        @endif

        {{ $areaWidget->contentBottom() }}

    </section>
@stop

@section('script')
    @parent

            <!-- Delete Article -->
    @if(Auth::check())
        @if(Auth::user()->is($user) || Auth::user()->isAdmin())
            <script type="text/javascript">
                $('.delete-article').click(function(){
                    var articleId = $(this).data('id');
                    if(confirm('Вы уверены, что хотите удалить статью?')) {
                        $.ajax({
                            url: '<?php echo URL::route('user.journal.delete', ['login' => $user->getLoginForUrl()]) ?>',
                            dataType: "text json",
                            type: "POST",
                            data: {articleId: articleId},
                            success: function(response) {
                                if(response.success){
                                    $('[data-article-id=' + articleId + ']').remove();
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endif
@stop