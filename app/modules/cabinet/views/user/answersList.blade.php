@if(count($answers))
    <section id="answers-area" class="blog">
        <div class="count">
            Показано ответов: <span>{{ $answers->count() }}</span>.
            Всего: <span>{{ $answers->getTotal() }}</span>.
        </div>

        @foreach($answers as $answer)
            <div data-comment-id="{{ $answer->id }}" id="answer-{{ $answer->id }}" class="well comment @if($answer->is_deleted) deleted @endif @if(!$answer->is_published) not-published @endif">
                <div class="row">
                    <div class="col-md-8 col-xs-8">
                        <div class="date date-created pull-left">
                            <span class="text">Ответ оставлен</span>
                            <span class="date">{{ DateHelper::dateFormat($answer->created_at) }}</span>
                        </div>
                        @if(!$answer->is_published)
                            <div class="not-published-text pull-right">
                                Ожидает модерации
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 col-xs-4">
                        @if(!$answer->is_deleted)
                            @if(Auth::check())
                                @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                                    <div class="buttons pull-right">
                                        <a href="javascript:void(0)" class="pull-right delete-answer" data-id="{{ $answer->id }}" title="Удалить комментарий" data-toggle="tooltip" data-placement="top">
                                            <i class="material-icons">delete</i>
                                        </a>
                                        <a href="{{ URL::route('admin.comments.edit', ['id' => $answer->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать ответ" data-toggle="tooltip">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    </div>
                                @elseif((Auth::user()->is($answer->user) && !Ip::isBanned() && !Auth::user()->is_banned && $answer->isEditable()))
                                    <div class="buttons pull-right">
                                        <a href="javascript:void(0)" class="pull-right delete-answer" data-id="{{ $answer->id }}" title="Удалить комментарий" data-toggle="tooltip" data-placement="top">
                                            <i class="material-icons">delete</i>
                                        </a>
                                        <a href="{{ URL::route('user.answers.edit', ['login' => $answer->user->getLoginForUrl(),'id' => $answer->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать ответ" data-toggle="tooltip">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="deleted-text pull-right">
                                Ответ удален.
                            </div>
                        @endif
                    </div>
                    <div class="col-md-10 col-xs-10">
                        <h3>
                            @if($answer->page)
                                <a href="{{ URL::to($answer->getUrl()) }}">
                                    {{ $answer->page->title }}
                                </a>
                            @else
                                страница удалена
                            @endif
                        </h3>
                        <div class="comment-text @if(Comment::MARK_BEST == $answer->mark) best @endif">
                            <div class="row">
                                @if(Comment::MARK_BEST != $answer->mark)
                                    <div class="col-md-12">
                                        {{ $answer->comment }}
                                    </div>
                                @else
                                    <div class="col-md-11 col-xs-11">
                                        {{ $answer->comment }}
                                    </div>
                                    <div class="col-md-1 col-xs-1">
                                        <div class="best pull-left" title="Ответ стал лучшим" data-toggle="tooltip">
                                            <i class="material-icons mdi-success">done</i>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-2">
                        <div class="vote" title="Оценка комментария" date-toggle="tooltip">
                            <div class="vote-dislike">
                                <i class="material-icons">arrow_drop_up</i>
                            </div>
                            <span class="vote-result" title="Рейтинг комментария" data-toggle="tooltip" data-placement="left">
                                {{ $answer->votes_like - $answer->votes_dislike }}
                            </span>
                            <div class="vote-dislike">
                                <i class="material-icons">arrow_drop_down</i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-xs-12">
                        @if(0 == $answer->parent_id)
                            <div class="answers">
                                Комментарии к ответу:
                                <a href="{{ URL::to($answer->getUrl()) }}">
                                    {{ count($answer->publishedChildren) }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        {{ $answers->links() }}
    </section>
@else
    @if(Auth::check())
        @if(Auth::user()->is($user))
            <p>
                Вы еще не ответили ни на один вопрос.
            </p>
        @else
            <p>
                Ответов нет.
            </p>
        @endif
    @else
        <p>
            Ответов нет.
        </p>
    @endif
@endif