@if(count($comments))

    <section id="comments-area" class="blog">
        <div class="count">
            Показано комментариев: <span>{{ $comments->count() }}</span>.
            Всего: <span>{{ $comments->getTotal() }}</span>.
        </div>

        @foreach($comments as $comment)
            <div data-comment-id="{{ $comment->id }}" id="comment-{{ $comment->id }}" class="well comment @if($comment->is_deleted) deleted @endif @if(!$comment->is_published) not-published @endif">
                <div class="row">
                    <div class="col-md-8 col-xs-8">
                        <div class="date date-created pull-left">
                            <span class="text">Комментарий оставлен</span>
                            <span class="date display-inline-block">{{ DateHelper::dateFormat($comment->created_at) }}</span>
                        </div>
                        <div class="not-published-text pull-right">
                            Ожидает модерации
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-4">
                        @if(!$comment->is_deleted)
                            @if(Auth::check())
                                @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                                    <div class="buttons pull-right">
                                        <a href="javascript:void(0)" class="delete-comment pull-right" data-id="{{ $comment->id }}" title="Удалить комментарий" data-toggle="tooltip" data-placement="top">
                                            <i class="material-icons">delete</i>
                                        </a>
                                        <a href="{{ URL::route('admin.comments.edit', ['id' => $comment->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать комментарий" data-toggle="tooltip">
                                            <i class="material-icons">mode_edit</i>
                                        </a>
                                    </div>
                                @elseif((Auth::user()->is($comment->user) && !Ip::isBanned() && !Auth::user()->is_banned && $comment->isEditable()))
                                    <div class="buttons pull-right">
                                        <a href="javascript:void(0)" class="delete-comment pull-right" data-id="{{ $comment->id }}" title="Удалить комментарий" data-toggle="tooltip" data-placement="top">
                                            <i class="material-icons">delete</i>
                                        </a>
                                        <a href="{{ URL::route('user.comments.edit', ['login' => $comment->user->getLoginForUrl(),'id' => $comment->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать комментарий" data-toggle="tooltip">
                                            <i class="material-icons">mode_edit</i>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="deleted-text pull-right">
                                Комментарий удален.
                            </div>
                        @endif
                    </div>
                    <div class="col-md-10 col-xs-10">
                        <h3>
                            @if($comment->page)
                                <a href="{{ URL::to($comment->getUrl()) }}">
                                    {{ $comment->page->title }}
                                </a>
                            @else
                                страница удалена
                            @endif
                        </h3>
                        <div class="comment-text">
                            {{ $comment->comment }}
                        </div>

                    </div>
                    <div class="col-md-2 col-xs-2">
                        <div class="vote" title="Оценка комментария" date-toggle="tooltip">
                            <div class="vote-dislike">
                                <i class="material-icons">arrow_drop_up</i>
                            </div>
                            <span class="vote-result" title="Рейтинг комментария" data-toggle="tooltip" data-placement="left">
                                {{ $comment->votes_like - $comment->votes_dislike }}
                            </span>
                            <div class="vote-dislike">
                                <i class="material-icons">arrow_drop_down</i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-xs-12">
                        @if(0 == $comment->parent_id)
                            <div class="answers">
                                Ответы на комментарий:
                                <a href="{{ URL::to($comment->getUrl()) }}">
                                    {{ count($comment->publishedChildren) }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        {{ $comments->links() }}
    </section>
@else
    @if(Auth::check())
        @if(Auth::user()->is($user))
            <p>
                Вы еще не создали ни одного комментария.
            </p>
        @else
            <p>
                Комментариев нет.
            </p>
        @endif
    @else
        <p>
            Комментариев нет.
        </p>
    @endif
@endif