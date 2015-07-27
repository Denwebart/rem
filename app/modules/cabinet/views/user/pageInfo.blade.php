<div class="col-md-10">
    <h3>
        @if(count($page->bestComments))
            <i class="icon mdi-action-done mdi-success"></i>
        @endif
        <a href="{{ URL::to($page->getUrl()) }}">
            {{ $page->title }}
        </a>
    </h3>
</div>
<div class="col-md-2">
    <div class="buttons">
        @if('user.savedPages' == Route::currentRouteName())
            <a href="javascript:void(0)" class="pull-right remove-page" data-id="{{ $page->id }}" title="Убрать статью из сохраненного" data-toggle="tooltip" data-placement="top">
                <i class="icon mdi-content-archive"></i>
            </a>
        @elseif('user.subscriptions' == Route::currentRouteName())
            <a href="javascript:void(0)" class="pull-right unsubscribe" data-id="{{ $page->id }}" title="Отписаться" data-toggle="tooltip" data-placement="top">
                {{--<i class="glyphicon glyphicon-floppy-remove"></i>--}}
                Отписаться
            </a>
        @endif
    </div>
</div>
<div class="col-md-12">
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
<div class="col-md-12">
    <div class="page-info">
        <div class="user pull-left">
            <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                <span class="login pull-left">{{ $page->user->login }}</span>
            </a>
        </div>
        <div class="date pull-left" title="Дата публикации" data-toggle="tooltip" data-placement="top">
            <span class="icon mdi-action-today"></span>
            <span>{{ DateHelper::dateFormat($page->published_at) }}</span>
        </div>
        <div class="pull-right">
            <div class="views pull-left" title="Количество просмотров" data-toggle="tooltip" data-placement="top">
                <span class="icon mdi-action-visibility"></span>
                <span>{{ $page->views }}</span>
            </div>
            @if(Page::TYPE_QUESTION == $page->type)
                <div class="comments-count pull-left" title="Количество ответов" data-toggle="tooltip" data-placement="top">
                    <span class="icon mdi-communication-forum"></span>
                    <a href="{{ URL::to($page->getUrl() . '#answers') }}">
                        {{ count($page->publishedAnswers) }}
                    </a>
                </div>
            @else
                <div class="comments-count pull-left" title="Количество комментариев" data-toggle="tooltip" data-placement="top">
                    <span class="icon mdi-communication-messenger"></span>
                    <a href="{{ URL::to($page->getUrl() . '#comments') }}">
                        {{ count($page->publishedComments) }}
                    </a>
                </div>
            @endif
            <div class="saved-count pull-left" title="Сколько пользователей сохранили" data-toggle="tooltip" data-placement="top">
                <span class="icon mdi-content-archive"></span>
                <span>{{ count($page->whoSaved) }}</span>
            </div>
            <div class="rating pull-left" title="Рейтинг (количество проголосовавших)" data-toggle="tooltip" data-placement="top">
                <span class="icon mdi-action-grade"></span>
                <span>{{ $page->getRating() }} ({{ $page->voters }})</span>
            </div>
            @if(Page::TYPE_QUESTION == $page->type)
                <div class="rating pull-left" title="Количество подписавшихся на вопрос" data-toggle="tooltip" data-placement="top">
                    <span class="icon mdi-maps-local-library"></span>
                    <span>{{ count($page->subscribers) }}</span>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="col-md-12">
    @if($page->parent)
        <div class="category pull-right">
            <div class="text pull-left">
                Категория:
            </div>
            <div class="link pull-left">
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
                    <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" title="{{ $tag->title }}" class="tag btn btn-sm btn-info">
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