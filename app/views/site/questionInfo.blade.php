<div class="row item" data-question-id="{{ $question->id }}">
    <div class="col-md-2">
        <div class="user">
            <a href="{{ URL::route('user.profile', ['login' => $question->user->getLoginForUrl()]) }}">
                {{ $question->user->getAvatar('mini', ['class' => 'pull-left']) }}
                <span class="login pull-left">{{ $question->user->login }}</span>
            </a>
        </div>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-12">
                <div class="date pull-left" title="Дата публикации">
                    <span class="mdi-action-today"></span>
                    {{ DateHelper::dateFormat($question->published_at) }}
                </div>
                <div class="pull-right">
                    <div class="views pull-left" title="Количество просмотров">
                        <span class="mdi-action-visibility"></span>
                        {{ $question->views }}
                    </div>
                    <div class="comments pull-left" title="Количество комментариев к ответам">
                        <span class="mdi-communication-messenger"></span>
                        <a href="{{ URL::to($question->getUrl() . '#answers') }}">
                            {{ count($question->publishedComments) }}
                        </a>
                    </div>                                        <div class="saved pull-left" title="Сколько пользователей сохранили">
                        <span class="mdi-content-archive"></span>
                        {{ count($question->whoSaved) }}
                    </div>
                    <div class="rating pull-left" title="Рейтинг (количество проголосовавших)">
                        <span class="mdi-action-grade"></span>
                        {{ $question->getRating() }} ({{ $question->voters }})
                    </div>
                    <div class="rating pull-left" title="Количество подписавшихся на вопрос">
                        <span class="mdi-maps-local-library"></span>
                        {{ count($question->subscribers) }}
                    </div>
                </div>
                @if(Auth::check())
                    @if((Auth::user()->is($question->user) && !IP::isBanned() && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                        <div class="buttons pull-left">
                            <a href="{{ URL::route('user.questions.edit', ['login' => Auth::user()->getLoginForUrl(),'id' => $question->id]) }}" class="btn btn-info btn-sm" title="Редактировать вопрос">
                                <span class="mdi-editor-mode-edit"></span>
                            </a>
                        </div>
                    @endif
                @endif
            </div>
            <div class="col-md-8">
                <h3>
                    <a href="{{ URL::to($question->getUrl()) }}">
                        {{ $question->title }}
                    </a>
                </h3>
                @if($page->id != $question->parent_id)
                    <div class="category">
                        Категория:
                        <a href="{{ URL::to($question->parent->getUrl()) }}">
                            {{ $question->parent->getTitle() }}
                        </a>
                    </div>
                @endif
            </div>
            <div class="col-md-2">
                <div class="answers">
                    Ответы:
                    @if(count($question->bestComments))
                        <i class="mdi-action-done mdi-success" style="font-size: 20pt;"></i>
                    @endif
                    <a href="{{ URL::to($question->getUrl()) }}#answers">
                        {{ count($question->publishedAnswers) }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<hr/>