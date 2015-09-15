<div class="row item" data-article-id="{{ $article->id }}">
    <div class="col-md-12">
        <h3>
            <a href="{{ URL::to($article->getUrl()) }}">
                {{ $article->title }}
            </a>
        </h3>
    </div>
    <div class="col-md-12">
        <div class="date pull-left hidden-lg hidden-md hidden-sm" title="Дата публикации" data-toggle="tooltip" data-placement="top">
            <i class="material-icons pull-left">today</i>
            <span class="pull-left">{{ DateHelper::dateFormat($article->published_at) }}</span>
        </div>
        <div class="page-info">
            <div class="pull-left">
                <div class="user pull-left">
                    <a href="{{ URL::route('user.profile', ['login' => $article->user->getLoginForUrl()]) }}">
                        {{ $article->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                        <span class="login pull-left">{{ $article->user->login }}</span>
                    </a>
                </div>
                <div class="date pull-left hidden-xs" title="Дата публикации" data-toggle="tooltip" data-placement="top">
                    <i class="material-icons">today</i>
                    <span>{{ DateHelper::dateFormat($article->published_at) }}</span>
                </div>
            </div>
            <div class="pull-right">
                <div class="views pull-left" title="Количество просмотров" data-toggle="tooltip" data-placement="top">
                    <i class="material-icons">visibility</i>
                    <span>{{ $article->views }}</span>
                </div>
                <div class="comments-count pull-left" title="Количество комментариев" data-toggle="tooltip" data-placement="top">
                    <i class="material-icons">chat_bubble</i>
                    <a href="{{ URL::to($article->getUrl() . '#comments') }}">
                        <span>{{ count($article->publishedComments) }}</span>
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
        @if(Request::is('/'))
            <div class="category pull-right">
                <div class="text pull-left">
                    Категория:
                </div>
                <div class="link pull-left">
                    @if($article->parent->parent)
                        <a href="{{ URL::to($article->parent->parent->getUrl()) }}">
                            {{ $article->parent->parent->getTitle() }}
                        </a>
                        /
                    @endif
                    <a href="{{ URL::to($article->parent->getUrl()) }}">
                        {{ $article->parent->getTitle() }}
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        @elseif($page->id != $article->parent_id)
            <div class="category pull-right">
                <div class="text pull-left">
                    Категория:
                </div>
                <div class="link pull-left">
                    <a href="{{ URL::to($article->parent->getUrl()) }}">
                        {{ $article->parent->getTitle() }}
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        @endif
        @if($article->image)
            <a href="{{ URL::to($article->getUrl()) }}" class="image">
                {{ $article->getImage() }}
            </a>
        @endif
        <p>{{ $article->getIntrotext() }}</p>
    </div>
    @if(Page::TYPE_ARTICLE == $article->type)
        @if(count($article->tags))
            <div class="col-md-12">
                <ul class="tags">
                    @foreach($article->tags as $tag)
                        <li>
                            <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" title="{{ $tag->title }}" class="tag btn btn-sm btn-primary">
                                {{ $tag->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif
    <div class="col-md-12">
        <a class="pull-right read-more" href="{{ URL::to($article->getUrl()) }}">
            Читать полностью
            <i class="material-icons">chevron_right</i>
        </a>
    </div>
</div>