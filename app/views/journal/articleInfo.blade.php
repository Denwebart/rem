<div class="row item" data-article-id="{{ $article->id }}">
    <div class="col-md-12">
        <h3>
            <a href="{{ URL::to($article->getUrl()) }}">
                {{ $article->title }}
            </a>
        </h3>
    </div>
    <div class="col-md-2">
    </div>
    <div class="col-md-10">
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
        @if(Auth::check())
            @if((Auth::user()->is($article->user) && !IP::isBanned() && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                <div class="buttons pull-left">
                    <a href="{{ URL::route('user.journal.edit', ['login' => $article->user->getLoginForUrl(),'id' => $article->id]) }}" class="btn btn-info btn-sm" title="Редактировать статью">
                        <span class="mdi-editor-mode-edit"></span>
                    </a>
                </div>
            @endif
        @endif
    </div>
    <div class="col-md-2">
        <div class="date" title="Дата публикации">
            <span class="mdi-action-today"></span>
            {{ DateHelper::dateFormat($article->published_at) }}
        </div>
        <div class="user">
            <a href="{{ URL::route('user.profile', ['login' => $article->user->getLoginForUrl()]) }}">
                {{ $article->user->getAvatar('mini', ['class' => 'pull-left']) }}
                <span class="login pull-left">{{ $article->user->login }}</span>
            </a>
        </div>
    </div>
    <div class="col-md-10">
        <div class="category pull-right">
            <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $article->user->getLoginForUrl()]) }}">
                Журнал: {{ $article->user->login }}
            </a>
        </div>
        <div class="clearfix"></div>
        @if($article->image)
            <a href="{{ URL::to($article->getUrl()) }}" class="image">
                {{ $article->getImage() }}
            </a>
        @endif
        <p>{{ $article->getIntrotext() }}</p>
        <ul class="tags">
            @foreach($article->tags as $tag)
                <li>
                    <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" title="{{ $tag->title }}">
                        {{ $tag->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-12">
        <a class="pull-right" href="{{ URL::to($article->getUrl()) }}">Читать полностью <span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
</div>