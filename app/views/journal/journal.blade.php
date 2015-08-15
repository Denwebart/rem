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
                        {{ $user->login }}
                        @if($user->getFullName())
                            ({{ $user->getFullName() }})
                        @endif
                    </a>
                </h2>

                <div class="content row journal-user-info">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <!-- Подписка на журнал пользователя ("Подписки") -->
                        @if(!Auth::user()->is($user))
                            @include('widgets.subscribe', ['subscriptionObject' => $user, 'subscriptionField' => Subscription::FIELD_JOURNAL_ID])
                        @endif
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
                    <section id="articles-area" class="blog">
                        <div class="count">
                            Показано статей: <span>{{ $articles->count() }}</span>.
                            Всего: <span>{{ $articles->getTotal() }}</span>.
                        </div>
                        @foreach($articles as $article)
                            <div data-article-id="{{ $article->id }}" class="well">
                                <div class="row">
                                    <div class="col-md-10">
                                        @if(Auth::check())
                                            @if((Auth::user()->is($article->user) && !$headerWidget->isBannedIp && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                                                <div class="status pull-left">
                                                    @if($article->is_published)
                                                        <i class="material-icons mdi-success" title="Опубликована" data-toggle="tooltip" data-placement="top">lens</i>
                                                    @else
                                                        <i class="material-icons mdi-danger" title="Не опубликована" data-toggle="tooltip" data-placement="top">lens</i>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                        <h3>
                                            <a href="{{ URL::to($article->getUrl()) }}">
                                                {{ $article->title }}
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="col-md-2">
                                        @if(Auth::check())
                                            @if((Auth::user()->is($article->user) && !$headerWidget->isBannedIp && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                                                <div class="buttons">
                                                    <a href="javascript:void(0)" class="pull-right delete-article" data-id="{{ $article->id }}" title="Удалить статью" data-toggle="tooltip" data-placement="top">
                                                        <i class="material-icons">delete</i>
                                                    </a>
                                                    <a href="{{ URL::route('user.journal.edit', ['login' => $user->getLoginForUrl(),'id' => $article->id]) }}" class="pull-right" title="Редактировать статью" data-toggle="tooltip" data-placement="top">
                                                        <i class="material-icons">mode-edit</i>
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <div class="page-info">
                                            <div class="date pull-left" title="Дата публикации" data-toggle="tooltip" data-placement="top">
                                                <i class="material-icons">today</i>
                                                <span>{{ DateHelper::dateFormat($article->published_at) }}</span>
                                            </div>
                                            <div class="pull-right">
                                                <div class="views pull-left" title="Количество просмотров" data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">visibility</i>
                                                    <span>{{ $article->views }}</span>
                                                </div>
                                                <div class="comments-count pull-left" title="Количество комментариев" data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">chat_bubble</i>
                                                    <a href="{{ URL::to($article->getUrl() . '#comments') }}">
                                                        {{ count($article->publishedComments) }}
                                                    </a>
                                                </div>
                                                <div class="saved-count pull-left" title="Сколько пользователей сохранили" data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">archive</i>
                                                    <span>{{ count($article->whoSaved) }}</span>
                                                </div>
                                                <div class="rating pull-left" title="Рейтинг (количество проголосовавших)" data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">grade</i>
                                                    <span>{{ $article->getRating() }} ({{ $article->voters }})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        @if($article->image)
                                            <a href="{{ URL::to($article->getUrl()) }}" class="image">
                                                {{ $article->getImage(null, ['width' => '200px']) }}
                                            </a>
                                        @endif
                                        <p>{{ $article->getIntrotext() }}</p>
                                        @if(count($article->tags))
                                            <ul class="tags">
                                                @foreach($article->tags as $tag)
                                                    <li>
                                                        <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" title="{{ $tag->title }}" class="tag btn btn-sm btn-info">
                                                            {{ $tag->title }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <a class="pull-right read-more" href="{{ URL::to($article->getUrl()) }}">
                                            Читать полностью
                                            <i class="material-icons">chevron_right</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{ $articles->links() }}
                    </section><!--blog-area-->
                @else
                    @if(Auth::check())
                        @if(Auth::user()->is($user))
                            <p>
                                Вы еще не создали ни одной статьи.
                            </p>
                        @else
                            <p>
                                Статей нет.
                            </p>
                        @endif
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
                                    $('#site-messages').prepend(response.message);
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