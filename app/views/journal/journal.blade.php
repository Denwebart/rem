@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        @include('widgets.breadcrumbs', ['items' => [
            [
                'title' => $journalParent->getTitle(),
                'url' => URL::to($journalParent->getUrl())
            ],
            [
                'title' => $user->login
            ]
        ]])

        <div class="row">
            <div class="col-lg-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <div itemscope itemtype="http://schema.org/Article">
                    <meta itemprop="datePublished" content="{{ DateHelper::dateFormatForSchema($user->created_at) }}">
                    <meta itemprop="image" content="{{ URL::to($user->getAvatarUrl()) }}">

                    <h2 class="margin-bottom-20" itemprop="headline">
                        Бортовой журнал пользователя
                        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="login">
                            {{ $user->login }}
                            @if($user->getFullName())
                                ({{ $user->getFullName() }})
                            @endif
                        </a>
                    </h2>
                </div>

                <div class="journal-user-info">
                    <div class="row">
                        <div class="col-xs-7 col-sm-8 col-md-8 col-lg-8">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="user-data-row">
                                        <i class="material-icons">chrome_reader_mode</i>
                                        <div class="text">
                                            Статей:
                                            <a href="{{ URL::route('user.journal', ['journalAlias' => $journalAlias, 'login' => $user->getLoginForUrl()]) }}">
                                                {{ count($user->publishedArticles) }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="user-data-row">
                                        <div>
                                            <i class="material-icons">help</i>
                                            <div class="text">
                                                Вопросов:
                                                <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}">
                                                    {{ count($user->publishedQuestions) }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="user-data-row">
                                        <i class="material-icons">question_answer</i>
                                        <div class="text">
                                            Ответов:
                                            <a href="{{ URL::route('user.answers', ['login' => $user->getLoginForUrl()]) }}">
                                                {{ count($user->publishedAnswers) }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="user-data-row">
                                        <i class="material-icons">chat_bubble</i>
                                        <div class="text">
                                            Комментариев:
                                            <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}">
                                                {{ count($user->publishedComments) }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-5 col-sm-4 col-md-4 col-lg-4">
                            <div class="points" title="Баллы" data-toggle="tooltip" data-placement="top">
                                {{ Html::image('images/coins.png', '', ['width' => '40px', 'class' => 'pull-left']) }}
                                <span class="count pull-left">
                                    {{ $user->points }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Подписка на журнал пользователя ("Подписки") -->
                @if(Auth::check())
                    @if(!Auth::user()->is($user))
                        <div class="row">
                            <div class="col-md-12">
                                @include('widgets.subscribe', ['subscriptionObject' => $user, 'subscriptionField' => Subscription::FIELD_JOURNAL_ID])
                            </div>
                        </div>
                    @endif
                @endif

                @if(Auth::check())
                    @if(Auth::user()->is($user))
                        @if(!$headerWidget->isBannedIp)
                            @if(!$user->is_banned)
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4 col-md-5 col-lg-4 col-sm-offset-8 col-md-offset-7 col-lg-offset-8">
                                        @if(Auth::user()->isAdmin())
                                            <a href="{{ URL::route('admin.articles.create', ['backUrl' => urlencode(Request::url())]) }}" class="btn btn-success btn-sm btn-full pull-right">
                                                Написать статью
                                            </a>
                                        @else
                                            <a href="{{ URL::route('user.journal.create', ['login' => Auth::user()->getLoginForUrl(), 'backUrl' => urlencode(Request::url())]) }}" class="btn btn-success btn-sm btn-full pull-right">
                                                Написать статью
                                            </a>
                                        @endif
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
                    <section id="articles-area" class="blog margin-top-10">
                        <div class="count">
                            Показано статей: <span>{{ $articles->count() }}</span>.
                            Всего: <span>{{ $articles->getTotal() }}</span>.
                        </div>
                        @foreach($articles as $article)
                            <div data-article-id="{{ $article->id }}" class="well @if(!$article->is_published) not-published @endif" itemscope itemtype="https://schema.org/BlogPosting">
                                <div class="row">
                                    @if(!$article->is_published)
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <div class="not-published-text pull-right">
                                                Ожидает модерации
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-10 col-md-9 col-xs-9">
                                        <h3 itemprop="headline name">
                                            <a href="{{ URL::to($article->getUrl()) }}">
                                                {{ $article->title }}
                                            </a>
                                        </h3>
                                    </div>
                                    <meta itemprop="author" content="{{ $article->user->login }}">
                                    <div class="col-lg-2 col-md-3 col-xs-3">
                                        @if(Auth::check())
                                            @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                                                <div class="buttons">
                                                    @if(Auth::user()->isAdmin())
                                                        <a href="javascript:void(0)" class="pull-right delete-article" data-id="{{ $article->id }}" title="Удалить статью" data-toggle="tooltip" data-placement="top">
                                                            <i class="material-icons">delete</i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ URL::route('admin.articles.edit', ['id' => $article->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать статью" data-toggle="tooltip" data-placement="top">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                </div>
                                            @elseif((Auth::user()->is($article->user) && !$headerWidget->isBannedIp && !Auth::user()->is_banned && $article->isEditable()))
                                                <div class="buttons">
                                                    <a href="javascript:void(0)" class="pull-right delete-article" data-id="{{ $article->id }}" title="Удалить статью" data-toggle="tooltip" data-placement="top">
                                                        <i class="material-icons">delete</i>
                                                    </a>
                                                    <a href="{{ URL::route('user.journal.edit', ['login' => $user->getLoginForUrl(),'id' => $article->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать статью" data-toggle="tooltip" data-placement="top">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <div class="date pull-left hidden-sm hidden-md hidden-lg">
                                            <i class="material-icons pull-left">today</i>
                                            <span class="pull-left">{{ DateHelper::dateFormat($article->published_at) }}</span>
                                        </div>
                                        <div class="page-info">
                                            <div class="date pull-left hidden-xs">
                                                <i class="material-icons">today</i>
                                                <time datetime="{{ DateHelper::dateFormatForSchema($article->published_at) }}" itemprop="datePublished">
                                                    {{ DateHelper::dateFormat($article->published_at) }}
                                                </time>
                                            </div>
                                            <div class="pull-right">
                                                <div class="views pull-left" title="Количество просмотров" data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">visibility</i>
                                                    <span>{{ $article->views }}</span>
                                                </div>
                                                <div class="comments-count pull-left" title="Количество комментариев" data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">chat_bubble</i>
                                                    <a href="{{ URL::to($article->getUrl() . '#comments') }}">
                                                        <span itemprop="commentCount">{{ count($article->publishedComments) }}</span>
                                                    </a>
                                                </div>
                                                <div class="saved-count pull-left" title="Сколько пользователей сохранили" data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">archive</i>
                                                    <span>{{ count($article->whoSaved) }}</span>
                                                </div>
                                                <div class="rating pull-left" title="Рейтинг (количество проголосовавших)" data-toggle="tooltip" data-placement="top" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                                                    <i class="material-icons">grade</i>
                                                    <span>
                                                        <meta itemprop="worstRating" content="0" />
                                                        <span itemprop="ratingValue">{{ $article->getRating() }}</span>
                                                        <meta itemprop="ratingCount" content="{{ $article->votes }}" />
                                                        (
                                                        <span itemprop="reviewCount">{{ $article->voters }}</span>
                                                        )
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        @if($article->image)
                                            <a href="{{ URL::to($article->getUrl()) }}" class="image">
                                                {{ $article->getImage(null, ['width' => '200px']) }}
                                            </a>
                                        @else
                                            <meta itemprop="image" content="{{ URL::to(Config::get('settings.defaultImage')) }}">
                                        @endif
                                        <div itemprop="description">
                                            {{ $article->getIntrotext() }}
                                        </div>
                                    </div>
                                    @if(count($article->tags))
                                        <div class="col-md-12">
                                            <ul class="tags">
                                                @foreach($article->tags as $tag)
                                                    <li>
                                                        <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" class="tag btn btn-sm btn-primary">
                                                            {{ $tag->title }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <a href="{{ URL::to($article->getUrl()) }}" class="read-more">
                                            <span class="link-text">
                                                <span>Читать полностью</span>
                                                <i class="material-icons">chevron_right</i>
                                            </span>
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
                                    setTimeout(function() {
                                        hideSiteMessage($('.site-message'));
                                    }, 2000);

                                    $('[data-article-id=' + articleId + ']').remove();
                                } else {
                                    $('#site-messages').prepend(response.message);
                                    setTimeout(function() {
                                        hideSiteMessage($('.site-message'));
                                    }, 2000);
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endif
@stop