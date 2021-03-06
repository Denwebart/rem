<div class="row item" data-question-id="{{ $question->id }}" itemscope itemtype="http://schema.org/Question">
    <div class="col-md-2 col-sm-2 col-xs-2" style="padding: 0">
        <div class="user" itemprop="author" itemscope itemtype="http://schema.org/Person">
            <a href="{{ URL::route('user.profile', ['login' => $question->user->getLoginForUrl()]) }}" class="avatar-link">
                {{ $question->user->getAvatar('mini', ['class' => 'pull-left avatar circle']) }}
                @if($question->user->isOnline())
                    <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                @else
                    <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($question->user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                @endif
            </a>
            <div class="clearfix"></div>
            <a href="{{ URL::route('user.profile', ['login' => $question->user->getLoginForUrl()]) }}" itemprop="url">
                <span class="login pull-left" itemprop="name">{{ $question->user->login }}</span>
            </a>
        </div>
    </div>
    <div class="col-md-10 col-sm-10 col-xs-10">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="date pull-left hidden-lg hidden-md hidden-sm" title="Дата публикации" data-toggle="tooltip" data-placement="top">
                    <i class="material-icons pull-left">today</i>
                    <span class="pull-left">{{ DateHelper::dateFormat($question->published_at) }}</span>
                </div>
                <div class="page-info">
                    <div class="date pull-left hidden-xs" title="Дата публикации" data-toggle="tooltip" data-placement="top">
                        <i class="material-icons">today</i>
                        <time datetime="{{ DateHelper::dateFormatForSchema($question->published_at) }}" itemprop="datePublished">
                            {{ DateHelper::dateFormat($question->published_at) }}
                        </time>
                    </div>
                    <div class="pull-right">
                        <div class="views pull-left" title="Количество просмотров" data-toggle="tooltip" data-placement="top">
                            <i class="material-icons">visibility</i>
                            <span>{{ $question->views }}</span>
                        </div>
                        <div class="saved-count pull-left" title="Сколько пользователей сохранили" data-toggle="tooltip" data-placement="top">
                            <i class="material-icons">archive</i>
                            <span>{{ count($question->whoSaved) }}</span>
                        </div>
                        <div class="rating pull-left" title="Рейтинг (количество проголосовавших)" data-toggle="tooltip" data-placement="top" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                            <i class="material-icons">grade</i>
                            <span>
                                <meta itemprop="worstRating" content="0" />
                                <span itemprop="ratingValue">{{ $question->getRating() }}</span>
                                <meta itemprop="ratingCount" content="{{ $question->votes }}" />
                                (
                                <span itemprop="reviewCount">{{ $question->voters }}</span>
                                )
                            </span>
                        </div>
                        <div class="subscribers pull-left" title="Количество подписавшихся на вопрос" data-toggle="tooltip" data-placement="top">
                            <i class="material-icons">local_library</i>
                            <span>{{ count($question->subscribers) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-9">
                <h3 itemprop="name">
                    <a href="{{ URL::to($question->getUrl()) }}">
                        {{ $question->title }}
                    </a>
                </h3>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="answers-text">
                    <span>Ответов:</span>
                </div>
                <div class="answers-value">
                    <a href="{{ URL::to($question->getUrl()) }}#answers" class="count @if(count($question->bestComments)) best @endif" itemprop="answerCount">
                        {{ count($question->publishedAnswers) }}
                    </a>
                    @if(count($question->bestComments))
                        <a href="{{ URL::to($question->getUrl()) }}#answers">
                            <i class="material-icons mdi-success" title="Есть решение">done</i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-8">
                @if($pageId != $question->parent_id)
                    <div class="category">
                        <div class="text pull-left hidden-xs">
                            Категория:
                        </div>
                        <div class="link pull-left">
                            <a href="{{ URL::to($question->parent->getUrl()) }}">
                                {{ $question->parent->getTitle() }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4">
                @if(Auth::check())
                    @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                        <div class="buttons pull-right">
                            <a href="{{ URL::route('admin.questions.edit', ['id' => $question->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать вопрос">
                                <i class="material-icons">mode_edit</i>
                            </a>
                        </div>
                    @elseif((Auth::user()->is($question->user) && !Ip::isBanned() && !Auth::user()->is_banned && $question->isEditable()))
                        <div class="buttons pull-right">
                            <a href="{{ URL::route('user.questions.edit', ['login' => $question->user->getLoginForUrl(),'id' => $question->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать вопрос">
                                <i class="material-icons">mode_edit</i>
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>