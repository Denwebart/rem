<div class="row item" data-question-id="{{ $question->id }}">
    <div class="col-md-2">
        <div class="user">
            <a href="{{ URL::route('user.profile', ['login' => $question->user->getLoginForUrl()]) }}" class="avatar-link">
                {{ $question->user->getAvatar('mini', ['class' => 'pull-left avatar circle']) }}
                @if($question->user->isOnline())
                    <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                @else
                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($question->user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                @endif
            </a>
            <a href="{{ URL::route('user.profile', ['login' => $question->user->getLoginForUrl()]) }}">
                <span class="login pull-left">{{ $question->user->login }}</span>
            </a>
        </div>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-12">
                <div class="page-info">
                    <div class="date pull-left" title="Дата публикации">
                        <i class="material-icons">today</i>
                        <span>{{ DateHelper::dateFormat($question->published_at) }}</span>
                    </div>
                    <div class="pull-right">
                        <div class="views pull-left" title="Количество просмотров">
                            <i class="material-icons">visibility</i>
                            <span>{{ $question->views }}</span>
                        </div>
                        <div class="saved-count pull-left" title="Сколько пользователей сохранили">
                            <i class="material-icons">archive</i>
                            <span>{{ count($question->whoSaved) }}</span>
                        </div>
                        <div class="rating pull-left" title="Рейтинг (количество проголосовавших)">
                            <i class="material-icons">grade</i>
                            <span>{{ $question->getRating() }} ({{ $question->voters }})</span>
                        </div>
                        <div class="subscribers pull-left" title="Количество подписавшихся на вопрос">
                            <i class="material-icons">local_library</i>
                            <span>{{ count($question->subscribers) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <h3>
                    <a href="{{ URL::to($question->getUrl()) }}">
                        {{ $question->title }}
                    </a>
                </h3>
            </div>
            <div class="col-md-1">
                @if(Auth::check())
                    @if((Auth::user()->is($question->user) && !IP::isBanned() && !Auth::user()->is_banned && $question->isEditable()) || Auth::user()->isAdmin())
                        <div class="buttons pull-right">
                            <a href="{{ URL::route('user.questions.edit', ['login' => $question->user->getLoginForUrl(),'id' => $question->id]) }}" class="" title="Редактировать вопрос">
                                <i class="material-icons">mode_edit</i>
                            </a>
                        </div>
                    @endif
                @endif
            </div>
            <div class="col-md-2">
                <div class="answers-text">
                    <span>Ответов:</span>
                </div>
                <div class="answers-value">
                    <a href="{{ URL::to($question->getUrl()) }}#answers" class="count @if(count($question->bestComments)) best @endif">
                        {{ count($question->publishedAnswers) }}
                    </a>
                    @if(count($question->bestComments))
                        <i class="material-icons mdi-success" title="Есть решение" style="font-size: 26px">done</i>
                    @endif
                </div>
            </div>
            <div class="col-md-9">
                @if($page->id != $question->parent_id)
                    <div class="category">
                        <div class="text pull-left">
                            Категория:
                        </div>
                        <div class="link pull-left">
                            <a href="{{ URL::to($question->parent->getUrl()) }}">
                                {{ $question->parent->getTitle() }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>