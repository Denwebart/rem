<div class="col-md-12">
    <h3>
        @if(count($page->bestComments))
            <i class="mdi-action-done mdi-success"></i>
        @endif
        <a href="{{ URL::to($page->getUrl()) }}">
            {{ $page->title }}
        </a>
        <div class="pull-right">
            @if('user.savedPages' == Route::currentRouteName())
                <a href="javascript:void(0)" class="remove-page" data-id="{{ $page->id }}">
                    <i class="glyphicon glyphicon-floppy-remove"></i>
                </a>
            @endif
            @if('user.subscriptions' == Route::currentRouteName())
                <a href="javascript:void(0)" class="unsubscribe" data-id="{{ $page->id }}">
                    {{--<i class="glyphicon glyphicon-floppy-remove"></i>--}}
                    Отписаться
                </a>
            @endif
        </div>
    </h3>
</div>
<div class="col-md-12">
    <div class="date date-saved">
        <i>
            Сохранено {{ DateHelper::dateFormat($item->created_at) }}
        </i>
    </div>
</div>
<div class="col-md-12">
    <div class="user pull-left">
        <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
            {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
            <span class="login pull-left">{{ $page->user->login }}</span>
        </a>
    </div>
    <div class="date pull-left" title="Дата публикации">
        <span class="mdi-action-today"></span>
        {{ DateHelper::dateFormat($page->published_at) }}
    </div>
    <div class="pull-right">
        <div class="views pull-left" title="Количество просмотров">
            <span class="mdi-action-visibility"></span>
            {{ $page->views }}
        </div>
        @if(Page::TYPE_QUESTION == $page->type)
            <div class="comments pull-left" title="Количество ответов">
                <span class="mdi-communication-forum"></span>
                <a href="{{ URL::to($page->getUrl() . '#answers') }}">
                    {{ count($page->publishedAnswers) }}
                </a>
            </div>
        @else
            <div class="comments pull-left" title="Количество комментариев">
                <span class="mdi-communication-messenger"></span>
                <a href="{{ URL::to($page->getUrl() . '#comments') }}">
                    {{ count($page->publishedComments) }}
                </a>
            </div>
        @endif
        <div class="saved pull-left" title="Сколько пользователей сохранили">
            <span class="mdi-content-archive"></span>
            {{ count($page->whoSaved) }}
        </div>
        <div class="rating pull-left" title="Рейтинг (количество проголосовавших)">
            <span class="mdi-action-grade"></span>
            {{ $page->getRating() }} ({{ $page->voters }})
        </div>
        @if(Page::TYPE_QUESTION == $page->type)
            <div class="rating pull-left" title="Количество подписавшихся на вопрос">
                <span class="mdi-maps-local-library"></span>
                {{ count($page->subscribers) }}
            </div>
        @endif
    </div>
</div>

<div class="col-md-12">
    @if($page->parent)
        <div class="category pull-right">
            Категория:
            @if($page->parent->parent)
                <a href="{{ URL::to($page->parent->parent->getUrl()) }}">
                    {{ $page->parent->parent->getTitle() }}
                </a>
                /
            @endif
            <a href="{{ URL::to($page->parent->getUrl()) }}">
                {{ $page->parent->getTitle() }}
            </a>
        </div>
        <div class="clearfix"></div>
    @endif

    @if($page->image)
        <a href="{{ URL::to($page->getUrl()) }}" class="image">
            {{ $page->getImage(null, ['width' => '200px']) }}
        </a>
    @endif
    <p>{{ $page->getIntrotext() }}</p>
    @if(count($page->tags))
        <ul class="tags">
            @foreach($page->tags as $tag)
                <li>
                    <a href="{{ URL::route('journal.tag', ['journalAlias' => $page->alias, 'tag' => $tag->title]) }}" title="{{ $tag->title }}">
                        {{ $tag->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
<div class="col-md-12">
    <a class="pull-right" href="{{ URL::to($page->getUrl()) }}">
        Читать полностью <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
</div>