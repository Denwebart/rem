<div class="row item" data-article-id="{{ $article->id }}">
    <div class="col-md-11 col-xs-11 col-sm-11">
        <h3>
            <a href="{{ URL::to($article->getUrl()) }}">
                {{ $article->title }}
            </a>
        </h3>
    </div>
    <div class="col-md-1 col-xs-1 col-sm-1">
        @if(Auth::check())
            @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                <div class="buttons pull-right">
                    <a href="{{ URL::route('admin.articles.edit', ['id' => $article->id, 'backUrl' => urlencode(Request::url())]) }}" class="" title="Редактировать статью">
                        <i class="material-icons">edit</i>
                    </a>
                </div>
            @elseif((Auth::user()->is($article->user) && !IP::isBanned() && !Auth::user()->is_banned && $article->isEditable()))
                <div class="buttons pull-right">
                    <a href="{{ URL::route('user.journal.edit', ['login' => $article->user->getLoginForUrl(),'id' => $article->id, 'backUrl' => urlencode(Request::url())]) }}" class="" title="Редактировать статью">
                        <i class="material-icons">edit</i>
                    </a>
                </div>
            @endif
        @endif
    </div>
    <div class="col-md-2 col-xs-2 col-sm-2">
        <div class="user">
            <a href="{{ URL::route('user.profile', ['login' => $article->user->getLoginForUrl()]) }}" class="avatar-link">
                {{ $article->user->getAvatar('mini', ['class' => 'pull-left avatar circle']) }}
                @if($article->user->isOnline())
                    <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                @else
                    <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($article->user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                @endif
            </a>
            <div class="clearfix"></div>
            <a href="{{ URL::route('user.profile', ['login' => $article->user->getLoginForUrl()]) }}">
                <span class="login pull-left">{{ $article->user->login }}</span>
            </a>
        </div>
    </div>
    <div class="col-md-10 col-xs-10 col-sm-10">
        <div class="date pull-left hidden-lg hidden-md hidden-sm" title="Дата публикации">
            <i class="material-icons pull-left">today</i>
            <span class="pull-left">{{ DateHelper::dateFormat($article->published_at) }}</span>
        </div>
        <div class="page-info">
            <div class="date pull-left hidden-xs" title="Дата публикации">
                <i class="material-icons">today</i>
                <span>{{ DateHelper::dateFormat($article->published_at) }}</span>
            </div>
            <div class="pull-right">
                <div class="views pull-left" title="Количество просмотров">
                    <i class="material-icons">visibility</i>
                    <span>{{ $article->views }}</span>
                </div>
                <div class="comments-count pull-left" title="Количество комментариев">
                    <i class="material-icons">chat_bubble</i>
                    <a href="{{ URL::to($article->getUrl() . '#comments') }}">
                        <span>{{ count($article->publishedComments) }}</span>
                    </a>
                </div>
                <div class="saved-count pull-left" title="Сколько пользователей сохранили">
                    <i class="material-icons">archive</i>
                    <span>{{ count($article->whoSaved) }}</span>
                </div>
                <div class="rating pull-left" title="Рейтинг (количество проголосовавших)">
                    <i class="material-icons">grade</i>
                    <span>{{ $article->getRating() }} ({{ $article->voters }})</span>
                </div>
            </div>
        </div>

        <div class="category pull-right">
            <div class="text pull-left">
                Журнал:
            </div>
            <div class="link pull-left">
                <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $article->user->getLoginForUrl()]) }}">
                    {{ $article->user->login }}
                </a>
            </div>
        </div>
        <div class="clearfix"></div>
        @if($article->image)
            <a href="{{ URL::to($article->getUrl()) }}" class="image">
                {{ $article->getImage() }}
            </a>
        @endif
        <p>{{ $article->getIntrotext() }}</p>
    </div>
    @if(count($article->tags))
        <div class="col-md-12 col-xs-12 col-sm-12">
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
    <div class="col-md-12">
        <a href="{{ URL::to($article->getUrl()) }}" class="read-more">
            <span class="link-text">
                <span>Читать полностью</span>
                <i class="material-icons">chevron_right</i>
            </span>
        </a>
    </div>
</div>