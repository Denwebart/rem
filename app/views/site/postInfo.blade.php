<div class="row item" data-article-id="{{ $article->id }}">
    <div class="col-md-12">
        <h3>
            <a href="{{ URL::to($article->getUrl()) }}">
                {{ $article->title }}
            </a>
        </h3>
    </div>
    <div class="col-md-12">
        <div class="user pull-left">
            <a href="{{ URL::route('user.profile', ['login' => $article->user->getLoginForUrl()]) }}">
                {{ $article->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                <span class="login pull-left">{{ $article->user->login }}</span>
            </a>
        </div>
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
        @if(Request::is('/'))
            <div class="category pull-right">
                Категория:
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
            <div class="clearfix"></div>
        @elseif($page->id != $article->parent_id)
            <div class="category pull-right">
                Категория:
                <a href="{{ URL::to($article->parent->getUrl()) }}">
                    {{ $article->parent->getTitle() }}
                </a>
            </div>
            <div class="clearfix"></div>
        @endif
        <a href="{{ URL::to($article->getUrl()) }}" class="image">
            {{ $article->getImage() }}
        </a>
        <p>{{ $article->getIntrotext() }}</p>
    </div>
    <div class="col-md-12">
        <a class="pull-right" href="#">Читать полностью <span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
</div>
<hr/>