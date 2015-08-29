<div class="row item" data-article-id="{{ $article->id }}">
    <div class="col-md-11">
        <h3>
            <a href="{{ URL::to($article->getUrl()) }}">
                {{ $article->title }}
            </a>
        </h3>
    </div>
    <div class="col-md-1">
        @if(Auth::check())
            @if((Auth::user()->is($article->user) && !IP::isBanned() && !Auth::user()->is_banned && $article->isEditable()) || Auth::user()->isAdmin())
                <div class="buttons pull-right">
                    <a href="{{ URL::route('user.journal.edit', ['login' => $article->user->getLoginForUrl(),'id' => $article->id]) }}" class="" title="Редактировать статью">
                        <i class="material-icons">edit_mode</i>
                    </a>
                </div>
            @endif
        @endif
    </div>
    <div class="col-md-2">
        <div class="user">
            <a href="{{ URL::route('user.profile', ['login' => $article->user->getLoginForUrl()]) }}" class="avatar-link">
                {{ $article->user->getAvatar('mini', ['class' => 'pull-left avatar circle']) }}
                @if($article->user->isOnline())
                    <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                @else
                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($article->user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                @endif
            </a>
            <a href="{{ URL::route('user.profile', ['login' => $article->user->getLoginForUrl()]) }}">
                <span class="login pull-left">{{ $article->user->login }}</span>
            </a>
        </div>
    </div>
    <div class="col-md-10">
        <div class="page-info">
            <div class="date pull-left" title="Дата публикации">
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
        <div class="col-md-12">
            <ul class="tags">
                @foreach($article->tags as $tag)
                    <li>
                        <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" title="{{ $tag->title }}" class="tag btn btn-sm btn-info">
                            {{ $tag->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="col-md-12">
        <a class="pull-right read-more" href="{{ URL::to($article->getUrl()) }}">
            Читать полностью
            <i class="material-icons">chevron_right</i>
        </a>
    </div>
</div>