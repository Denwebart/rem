<div id="comment-{{ $comment->id }}" class="media">
    <a href="javascript:void(0)" class="pull-left close-comment">-</a>
    <a class="pull-left" href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}">
        {{ $comment->user->getAvatar('mini', ['class' => 'media-object']) }}
    </a>
    <div class="media-body">
        <h4 class="media-heading">
            <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}" class="author">{{ $comment->user->login }}</a>
            <small>{{ DateHelper::dateFormat($comment->created_at) }}</small>
        </h4>
        <div>{{ $comment->comment }}</div>

        <div class="vote pull-right" data-vote-comment-id="{{ $comment->id }}">
            @if(Auth::check())
                @if(!Auth::user()->is($comment->user))
                    <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
                    <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
                    <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                    <div class="vote-message"></div>
                @endif
            @endif
        </div>

        @if($comment->mark == Comment::MARK_BEST)
            <div class="mark-comment pull-right" data-mark-comment-id="{{ $comment->id }}">
                <i class="mdi-action-done mdi-success" style="font-size: 40pt;"></i>
            </div>
        @elseif(Auth::check())
            @if(Auth::user()->is($comment->page->user) && $comment->page->type == Page::TYPE_QUESTION && !$comment->page->bestComment)
                <div class="mark-comment pull-right" data-mark-comment-id="{{ $comment->id }}">
                    {{--<a href="javascript:void(0)" class="pull-left mark-comment-as-good">Хороший</a>--}}
                    <a href="javascript:void(0)" class="pull-left mark-comment-as-best">
                        <i class="mdi-action-done mdi-material-grey" style="font-size: 40pt;"></i>
                    </a>
                </div>
            @endif
        @endif

        <a href="javascript:void(0)" class="reply" data-comment-id="{{ $comment->id }}">Ответить</a>

        <div class="reply-comment-form" id="reply-comment-form-{{$comment->id}}" style="display: none;">

            @if(Auth::check())

                {{ Form::open([
                      'action' => ['CommentsController@addComment', $comment->page_id],
                      'id' => 'comment-form-' . $comment->id,
                    ])
                }}

                <div class="successMessage"></div>

                {{ Form::hidden('parent_id', $comment->id); }}

                <a href="{{ URL::route('user.profile', ['login' => Auth::user()->getLoginForUrl()]) }}">
                    {{ Auth::user()->getAvatar('mini', ['class' => 'media-object']) }}
                    <span>{{  Auth::user()->login }}</span>
                </a>

                <div class="form-group">
                    {{ Form::textarea('comment', '', ['class' => 'form-control editor', 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                    <div class="comment_error error text-danger"></div>
                </div>

                {{ Form::submit('Отправить', ['id'=> 'submit-' . $comment->id, 'class' => 'btn btn-prime btn-mid']) }}

                {{ Form::close() }}
            @else
                Зарегистрируйтесь или войдите
                {{ HTML::link(URL::to('users/login'), 'Вход') }}
                {{ HTML::link(URL::to('users/register'), 'Регистрация') }}
            @endif
        </div>

    </div>
</div>