<div id="comment-{{ $comment->id }}" class="media">
    <a href="javascript:void(0)" class="pull-left close-comment">-</a>
    @if($comment->user)
        <a class="pull-left" href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}">
            {{ $comment->user->getAvatar('mini', ['class' => 'media-object']) }}
        </a>
    @else
        <a class="pull-left" href="javascript:void(0)">
            {{ (new User)->getAvatar('mini', ['class' => 'media-object']) }}
        </a>
    @endif
    <div class="media-body">
        <h4 class="media-heading">
            @if($comment->user)
                <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}" class="author{{ ($page->user_id == $comment->user_id) ? ' page-author' : '' }}">
                    {{ $comment->user->login }}
                </a>
            @else
                <a href="javascript:void(0)" class="author">
                    {{ $comment->user_name }}
                </a>
            @endif
            <small>{{ DateHelper::dateFormat($comment->created_at) }}</small>
            @if(Auth::check())
                @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                    <a href="{{ URL::route('admin.comments.edit', ['id' => $comment->id]) }}">
                        <i class="mdi-content-create"></i>
                    </a>
                @endif()
            @endif()
        </h4>
        <div>{{ $comment->comment }}</div>

        @include('widgets.comment.vote')

        {{--Отметить лучшие ответы--}}
        @if($comment->mark == Comment::MARK_BEST)
            <div class="mark-comment" data-mark-comment-id="{{ $comment->id }}">
                <i class="mdi-action-done mdi-success" style="font-size: 40pt;" title="Лучший ответ"></i>
            </div>
        @elseif(Auth::check())
            @if(Auth::user()->is($page->user) && $page->type == Page::TYPE_QUESTION)
                <div class="mark-comment" data-mark-comment-id="{{ $comment->id }}">
                    <a href="javascript:void(0)" class="pull-left btn btn-sm mark-comment-as-best" title="Если ответ вам помог, отметьте его как лучший.">
                        Отметить как лучший
                    </a>
                </div>
            @endif
        @endif

        <a href="javascript:void(0)" class="pull-left btn btn-sm reply" data-comment-id="{{ $comment->id }}">Ответить</a>
        <div class="clearfix"></div>

        <div class="reply-comment-form" id="reply-comment-form-{{$comment->id}}" style="display: none;">

            @if(!Ip::isBanned())
                @if(Auth::check())
                    @if(!Auth::user()->is_banned)
                        @if(!Auth::user()->is_agree)
                            <div class="alert alert-dismissable alert-warning">
                                <h4>Вы не согласились с правилами сайта!</h4>
                                <p>Пока вы не соглавитесь с правилами сайта,
                                    вы не сможете оставлять комментарии.
                                    Для ознакомления с правилами перейдите по <a href="{{ URL::route('rules', ['rulesAlias' => 'rules', 'backUrl' => urlencode(Request::url())]) }}" class="alert-link">ссылке</a>.</p>
                            </div>
                        @else

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
                        @endif
                    @else
                        @include('cabinet::user.banMessage')
                    @endif
                @else
                    {{ Form::open([
                              'action' => ['CommentsController@addComment', $page->id],
                              'id' => 'comment-form-' . $comment->id,
                            ])
                        }}

                    <div class="successMessage"></div>

                    {{ Form::hidden('parent_id', $comment->id); }}

                    <div class="row">
                        <div class="col-md-6 form-group">
                            {{ Form::text('user_name', Session::has('user.user_name') ? Session::get('user.user_name') : '', ['class' => 'form-control', 'placeholder' => 'Имя*']); }}
                            <div class="user_name_error error text-danger"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            {{ Form::text('user_email', Session::has('user.user_email') ? Session::get('user.user_email') : '', ['class' => 'form-control', 'placeholder' => 'Email*']); }}
                            <div class="user_email_error error text-danger"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::textarea('comment', '', ['class' => 'form-control editor', 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                        <div class="comment_error error text-danger"></div>
                    </div>

                    {{ Form::captcha() }}
                    @if ($errors->has('g-recaptcha-response'))
                        <p class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                    @endif

                    {{ Form::submit('Отправить', ['id'=> 'submit-' . $comment->id, 'class' => 'btn btn-prime btn-mid']) }}

                    {{ Form::close() }}
                @endif
            @else
                @include('messages.bannedIp')
            @endif
        </div>

        <!-- Nested Comment -->
        <div class="children-comments">
            @foreach($comment->publishedChildren as $commentLevel2)
                @include('widgets.comment.comment2Level', ['page' => $page, 'commentLevel2' => $commentLevel2])
            @endforeach
        </div>
        <!-- End Nested Comment -->
    </div>
</div>