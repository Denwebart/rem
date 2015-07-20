@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="col-lg-3 col-md-3">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
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

        <div class="row">
            <div class="col-lg-12" id="content">
                <h2>
                    Бортовой журнал пользователя
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="login">
                        {{ $user->login }} ({{ $user->getFullName() }})
                    </a>
                </h2>

                <div class="content row journal-user-info">
                    <div class="col-md-12">
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
                    </div>
                </div>

                @if(Auth::check())
                    @if(Auth::user()->is($user))
                        @if(!$headerWidget->isBannedIp)
                            @if(!$user->is_banned)
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ URL::route('user.journal.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                                            Написать статью
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                @endif

                @if(Auth::check())
                    @if(Auth::user()->is($user))
                        @if($headerWidget->isBannedIp)
                            <div class="row">
                                <div class="col-md-12">
                                    @include('messages.bannedIp')
                                </div>
                            </div>
                        @endif
                        @if($user->is_banned)
                            <div class="row">
                                <div class="col-md-12">
                                    @include('cabinet::user.banMessage')
                                </div>
                            </div>
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
                            <div data-article-id="{{ $article->id }}" class="well">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>
                                            <a href="{{ URL::to($article->getUrl()) }}">
                                                {{ $article->title }}
                                            </a>
                                            <div class="pull-right">
                                                @if(Auth::check())
                                                    @if((Auth::user()->is($article->user) && !$headerWidget->isBannedIp && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                                                        <div class="buttons pull-left">
                                                            <a href="{{ URL::route('user.journal.edit', ['login' => $user->getLoginForUrl(),'id' => $article->id]) }}" class="btn btn-info btn-sm" title="Редактировать статью">
                                                                <span class="mdi-editor-mode-edit"></span>
                                                            </a>
                                                            <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-article" data-id="{{ $article->id }}" title="Удалить статью">
                                                                <span class="mdi-content-clear"></span>
                                                            </a>
                                                            <div class="status">
                                                                Статус:
                                                                {{ ($article->is_published) ? 'Опубликована' : 'Неопубликована' }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="date pull-left" title="Дата публикации">
                                            <span class="mdi-action-today"></span>
                                            {{ DateHelper::dateFormat($article->published_at) }}
                                        </div>
                                        <div class="pull-right">
                                            <div class="views pull-left" title="Количество просмотров">
                                                <span class="mdi-action-visibility"></span>
                                                {{ $article->views }}
                                            </div>
                                            <div class="comments pull-left" title="Количество комментариев">
                                                <span class="mdi-communication-messenger"></span>
                                                <a href="{{ URL::to($article->getUrl() . '#comments') }}">
                                                    {{ count($article->publishedComments) }}
                                                </a>
                                            </div>
                                            <div class="saved pull-left" title="Сколько пользователей сохранили">
                                                <span class="mdi-content-archive"></span>
                                                {{ count($article->whoSaved) }}
                                            </div>
                                            <div class="rating pull-left" title="Рейтинг (количество проголосовавших)">
                                                <span class="mdi-action-grade"></span>
                                                {{ $article->getRating() }} ({{ $article->voters }})
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <a href="{{ URL::to($article->getUrl()) }}" class="image">
                                            {{ $article->getImage(null, ['width' => '200px']) }}
                                        </a>
                                        <p>{{ $article->getIntrotext() }}</p>
                                        @if(count($article->tags))
                                            <ul class="tags">
                                                @foreach($article->tags as $tag)
                                                    <li>
                                                        <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" title="{{ $tag->title }}">
                                                            {{ $tag->title }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <a class="pull-right" href="{{ URL::to($article->getUrl()) }}">
                                            Читать полностью <span class="glyphicon glyphicon-chevron-right"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{ $articles->links() }}
                    </section><!--blog-area-->
                @else
                    @if(Auth::user()->is($user))
                        <p>
                            Вы еще не создали ни одной статьи.
                        </p>
                    @else
                        <p>
                            Статей нет.
                        </p>
                    @endif
                @endif

            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
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
                            beforeSend: function(request) {
                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                            },
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