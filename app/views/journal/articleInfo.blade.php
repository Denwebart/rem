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
            @if((Auth::user()->is($article->user) && !IP::isBanned() && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                <div class="buttons pull-right">
                    <a href="{{ URL::route('user.journal.edit', ['login' => $article->user->getLoginForUrl(),'id' => $article->id]) }}" class="" title="Редактировать статью">
                        <span class="icon mdi-editor-mode-edit"></span>
                    </a>
                </div>
            @endif
        @endif
    </div>
    <div class="col-md-2">
        <div class="user">
            <a href="{{ URL::route('user.profile', ['login' => $article->user->getLoginForUrl()]) }}">
                {{ $article->user->getAvatar('mini', ['class' => 'pull-left']) }}
            </a>
            <a href="{{ URL::route('user.profile', ['login' => $article->user->getLoginForUrl()]) }}">
                <span class="login pull-left">{{ $article->user->login }}</span>
            </a>
        </div>
    </div>
    <div class="col-md-10">
        <div class="page-info">
            <div class="date pull-left" title="Дата публикации">
                <span class="icon mdi-action-today"></span>
                <span>{{ DateHelper::dateFormat($article->published_at) }}</span>
            </div>
            <div class="pull-right">
                <div class="views pull-left" title="Количество просмотров">
                    <span class="icon mdi-action-visibility"></span>
                    <span>{{ $article->views }}</span>
                </div>
                <div class="comments-count pull-left" title="Количество комментариев">
                    <span class="icon mdi-communication-messenger"></span>
                    <a href="{{ URL::to($article->getUrl() . '#comments') }}">
                        <span>{{ count($article->publishedComments) }}</span>
                    </a>
                </div>
                <div class="saved-count pull-left" title="Сколько пользователей сохранили">
                    <span class="icon mdi-content-archive"></span>
                    <span>{{ count($article->whoSaved) }}</span>
                </div>
                <div class="rating pull-left" title="Рейтинг (количество проголосовавших)">
                    <span class="icon mdi-action-grade"></span>
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
        <a class="pull-right" href="{{ URL::to($article->getUrl()) }}">Читать полностью <span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
</div>