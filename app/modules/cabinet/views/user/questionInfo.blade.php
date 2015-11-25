<div class="col-md-11 col-xs-10">
    @if('user.savedPages' == Route::currentRouteName())
        <div class="date date-saved">
            <span class="text">Сохранено</span>
            <span class="date">{{ DateHelper::dateFormat($item->created_at) }}</span>
        </div>
    @elseif('user.subscriptions' == Route::currentRouteName())
        <div class="date date-saved">
            <span class="text">Подписка оформлена</span>
            <span class="date">{{ DateHelper::dateFormat($item->created_at) }}</span>
        </div>
    @endif
</div>
<div class="col-md-1 col-xs-2">
    <div class="buttons without-margin">
        @if(Auth::user()->is($user))
            @if('user.savedPages' == Route::currentRouteName())
                <a href="javascript:void(0)" class="pull-right remove-page" data-id="{{ $page->id }}" title="Убрать статью из сохраненного" data-toggle="tooltip" data-placement="top">
                    <i class="material-icons">close</i>
                </a>
            @elseif('user.subscriptions' == Route::currentRouteName())
                <a href="javascript:void(0)" class="pull-right unsubscribe" data-subscription-field="{{ Subscription::FIELD_PAGE_ID }}" data-subscription-object-id="{{ $subscription->page_id }}" title="Отписаться" data-toggle="tooltip" data-placement="top">
                    <i class="material-icons">close</i>
                </a>
            @endif
        @endif
    </div>
</div>
<div class="col-md-12 col-xs-12 margin-top-10">
    <div class="row">
        <div class="col-md-2 col-xs-2">
            <div class="user">
                <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}" class="avatar-link display-inline-block">
                    {{ $page->user->getAvatar('mini', ['class' => 'pull-left avatar circle']) }}
                    @if($page->user->isOnline())
                        <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                    @else
                        <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($page->user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                    @endif
                </a>
                <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                    <span class="login pull-left">{{ $page->user->login }}</span>
                </a>
            </div>
        </div>
        <div class="col-md-10 col-xs-10">
            <div class="row">
                <div class="col-md-12">
                    <div class="date pull-left hidden-lg hidden-md hidden-sm">
                        <i class="material-icons pull-left">today</i>
                        <span class="pull-left">{{ DateHelper::dateFormat($page->published_at) }}</span>
                    </div>
                    <div class="page-info">
                        <div class="date pull-left hidden-xs">
                            <i class="material-icons">today</i>
                            <span>{{ DateHelper::dateFormat($page->published_at) }}</span>
                        </div>
                        <div class="pull-right">
                            <div class="views pull-left" title="Количество просмотров" data-toggle="tooltip" data-placement="top">
                                <i class="material-icons">visibility</i>
                                <span>{{ $page->views }}</span>
                            </div>
                            <div class="saved-count pull-left" title="Сколько пользователей сохранили" data-toggle="tooltip" data-placement="top">
                                <i class="material-icons">archive</i>
                                <span>{{ count($page->whoSaved) }}</span>
                            </div>
                            <div class="rating pull-left" title="Рейтинг (количество проголосовавших)" data-toggle="tooltip" data-placement="top" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                                <i class="material-icons">grade</i>
                                <span>
                                    <span itemprop="ratingValue">{{ $page->getRating() }}</span>
                                    <meta itemprop="ratingCount" content="{{ $page->votes }}" />
                                    (
                                    <span itemprop="reviewCount">{{ $page->voters }}</span>
                                    )
                                </span>
                            </div>
                            <div class="subscribers pull-left" title="Количество подписавшихся на вопрос" data-toggle="tooltip" data-placement="top">
                                <i class="material-icons">local_library</i>
                                <span>{{ count($page->subscribers) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-xs-9">
                    <h3>
                        <a href="{{ URL::to($page->getUrl()) }}">
                            {{ $page->title }}
                        </a>
                    </h3>
                </div>
                <div class="col-md-3 col-xs-3">
                    <div class="answers-text">
                        <span>Ответов:</span>
                    </div>

                    <div class="answers-value">
                        <a href="{{ URL::to($page->getUrl()) }}#answers" class="count @if(count($page->bestComments)) best @endif">
                            {{ count($page->publishedAnswers) }}
                        </a>
                        @if(count($page->bestComments))
                            <a href="{{ URL::to($page->getUrl()) }}#answers">
                                <i class="material-icons mdi-success" title="Есть решение" data-toggle="tooltip" data-placement="top">done</i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-9 col-xs-8">
                    <div class="category margin-top-10">
                        <div class="text pull-left hidden-xs">
                            Категория:
                        </div>
                        <div class="link pull-left">
                            <a href="{{ URL::to($page->parent->getUrl()) }}">
                                {{ $page->parent->getTitle() }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-4">
                    @if(Auth::check())
                        @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                            <div class="buttons pull-right">
                                <a href="{{ URL::route('admin.questions.edit', ['id' => $page->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать вопрос" data-toggle="tooltip" data-placement="top">
                                    <i class="material-icons">edit</i>
                                </a>
                            </div>
                        @elseif((Auth::user()->is($page->user) && !Ip::isBanned() && !Auth::user()->is_banned && $page->isEditable()))
                            <div class="buttons">
                                <a href="{{ URL::route('user.questions.edit', ['login' => $page->user->getLoginForUrl(),'id' => $page->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать вопрос" data-toggle="tooltip" data-placement="top">
                                    <i class="material-icons">edit</i>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>