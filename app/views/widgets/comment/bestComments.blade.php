<h3>Лучшие ответы
    <span class="count-best-comments">
        ({{ count($page->bestComments) }})
    </span>
</h3>

@foreach($bestComments as $comment)
    <!-- Comment -->
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

            {{--Отметка лучшего ответа--}}
            <div class="mark-comment pull-right" data-mark-comment-id="{{ $comment->id }}">
                <i class="mdi-action-done mdi-success" style="font-size: 40pt;"></i>
            </div>

            <a href="javascript:void(0)" class="reply" data-comment-id="{{ $comment->id }}">Ответить</a>

            <div class="reply-comment-form" id="reply-comment-form-{{$comment->id}}" style="display: none;">

                @if(Auth::check())

                    {{ Form::open([
                          'action' => ['CommentsController@addComment', $page->id],
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

            <!-- Nested Comment -->
            @if(count($comment->publishedChildren))
                <div class="children-comments">
                    @foreach($comment->publishedChildren as $commentLevel2)
                        <div class="media" id="comment-{{ $commentLevel2->id }}" >
                            <a class="pull-left" href="{{ URL::route('user.profile', ['login' => $commentLevel2->user->getLoginForUrl()]) }}">
                                {{ $commentLevel2->user->getAvatar('mini', ['class' => 'media-object']) }}
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <a href="{{ URL::route('user.profile', ['login' => $commentLevel2->user->getLoginForUrl()]) }}" class="author">{{ $commentLevel2->user->login }}</a>
                                    <small>{{ DateHelper::dateFormat($commentLevel2->created_at) }}</small>
                                </h4>
                                <div>{{ $commentLevel2->comment }}</div>

                                <div class="vote pull-right" data-vote-comment-id="{{ $commentLevel2->id }}">
                                    <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
                                    <span class="vote-result">{{ $commentLevel2->votes_like - $commentLevel2->votes_dislike }}</span>
                                    <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                                    <div class="vote-message"></div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <!-- End Nested Comment -->
        </div>
    </div>
@endforeach