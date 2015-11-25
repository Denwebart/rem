<div class="row item" data-article-id="{{ $article->id }}" itemscope itemprop="https://schema.org/BlogPosting">
    <div class="col-md-12">
        <h3 itemprop="name">
            <a href="{{ URL::to($article->getUrl()) }}">
                {{ $article->title }}
            </a>
        </h3>
    </div>
    <div class="col-md-12">
        <div class="date pull-left hidden-lg hidden-md hidden-sm">
            <i class="material-icons pull-left">today</i>
            <span class="pull-left">{{ DateHelper::dateFormat($article->published_at) }}</span>
        </div>
        <div class="page-info">
            <div class="pull-left">
                <div class="user pull-left" itemprop="author" itemscope itemtype="http://schema.org/Person">
                    <a href="{{ URL::route('user.profile', ['login' => $article->user->getLoginForUrl()]) }}" itemprop="url">
                        {{ $article->user->getAvatar('mini', ['width' => '25', 'class' => 'pull-left']) }}
                        <span class="login pull-left" itemprop="name">{{ $article->user->login }}</span>
                    </a>
                </div>
                <div class="date pull-left hidden-xs">
                    <i class="material-icons">today</i>
                    <time datetime="{{ DateHelper::dateFormatForSchema($article->published_at) }}" itemprop="datePublished">
                        {{ DateHelper::dateFormat($article->published_at) }}
                    </time>
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
        @if(Request::is('/'))
            <div class="category pull-right">
                <div class="text pull-left">
                    Категория:
                </div>
                <div class="link pull-left">
                    @if($article->parent->parent)
                        <a href="{{ URL::to($article->parent->parent->getUrl()) }}">
                            @if($article->parent->parent->menuItem)
                                {{ $article->parent->parent->menuItem->menu_title }}
                            @else
                                {{ $article->parent->parent->title }}
                            @endif
                        </a>
                        /
                    @endif
                    <a href="{{ URL::to($article->parent->getUrl()) }}">
                        @if($article->parent->menuItem)
                            {{ $article->parent->menuItem->menu_title }}
                        @else
                            {{ $article->parent->title }}
                        @endif
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        @elseif($page->id != $article->parent_id)
            <div class="category pull-right">
                <div class="text pull-left">
                    Категория:
                </div>
                <div class="link pull-left" itemprop="articleSection">
                    <a href="{{ URL::to($article->parent->getUrl()) }}">
                        @if($article->parent->menuItem)
                            {{ $article->parent->menuItem->menu_title }}
                        @else
                            {{ $article->parent->title }}
                        @endif
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
        <div itemprop="description">
            {{ $article->getIntrotext() }}
        </div>
    </div>
    @if(Page::TYPE_ARTICLE == $article->type)
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