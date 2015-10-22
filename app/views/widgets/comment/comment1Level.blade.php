<div id="comment-{{ $comment->id }}" class="comment">
    <a href="javascript:void(0)" class="close-comment" @if(!count($comment->publishedChildren)) style="display: none" @endif title="Скрыть дочерние комментарии" data-toggle="tooltip">
        <i class="material-icons">keyboard_arrow_up</i>
    </a>
    @if(!$comment->is_deleted)
        <div class="parent-comment comment-text @if($comment->mark == Comment::MARK_BEST) best @endif">
            <div class="row">
                <div class="col-md-11 col-sm-11 col-xs-10">
                    @if($comment->user)
                        <a class="pull-left avatar-link gray-background" href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}">
                            {{ $comment->user->getAvatar('mini', ['class' => 'media-object avatar circle']) }}
                            @if($comment->user->isOnline())
                                <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                            @else
                                <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($comment->user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                            @endif
                        </a>
                    @else
                        <a class="pull-left avatar-link" href="javascript:void(0)">
                            {{ (new User)->getAvatar('mini', ['class' => 'media-object avatar circle']) }}
                        </a>
                    @endif
                    <div class="media-body">
                        <div class="media-heading author">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        @if($comment->user)
                                            <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}" class="login {{ ($page->user_id == $comment->user_id) ? ' page-author' : '' }}">
                                                {{ $comment->user->login }}
                                            </a>
                                        @else
                                            <a href="javascript:void(0)" class="login">
                                                {{ $comment->user_name }}
                                            </a>
                                        @endif
                                        <br>
                                        <span class="date">
                                            {{ DateHelper::dateFormat($comment->created_at) }}
                                        </span>
                                        <a href="{{ URL::to($page->getUrl()) }}#comment-{{ $comment->id }}" class="get-link" data-comment-id="{{ $comment->id }}" title="Ссылка на комментарий" data-toggle="tooltip">
                                            <span>#</span>
                                        </a>
                                    </div>

                                    @if($comment->mark == Comment::MARK_BEST)
                                        <div class="best-comment pull-left" data-mark-comment-id="{{ $comment->id }}">
                                            <i class="material-icons mdi-success" title="Лучший ответ" data-toggle="tooltip" data-placement="top">done</i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="comment-content" data-parent-id="{{ $comment->id }}">
                            {{ StringHelper::addFancybox($comment->comment, 'group-comment-' . $comment->id) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-2">
                    @include('widgets.comment.vote', ['isBannedIp' => $isBannedIp,])
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="javascript:void(0)" class="pull-left reply" data-comment-id="{{ $comment->id }}">
                                <span>Ответить</span>
                            </a>
                            {{--Отметить лучшие ответы--}}
                            @if($comment->mark != Comment::MARK_BEST && Auth::check())
                                @if(Auth::user()->is($page->user) && $page->type == Page::TYPE_QUESTION)
                                    <div class="mark-comment" data-mark-comment-id="{{ $comment->id }}">
                                        <a href="javascript:void(0)" class="pull-left mark-comment-as-best" title="Если ответ вам помог, отметьте его как лучший." data-toggle="tooltip" data-placement="top">
                                            <div class="hidden-xs">Отметить как лучший</div>
                                            <div class="hidden-lg hidden-md hidden-sm">Лучший</div>
                                        </a>
                                    </div>
                                @endif
                            @endif

                            @if(Auth::check())
                                @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                                    <div class="buttons pull-right">
                                        <a href="{{ URL::route('admin.comments.edit', ['id' => $comment->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-left margin-left-20" title="Редактировать комментарий" data-toggle="tooltip" data-placement="top">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="javascript:void(0)" class="pull-left margin-left-10 delete-comment" title="Удалить комментарий" data-id="{{ $comment->id }}" data-toggle="tooltip" data-placement="top">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="reply-comment-form" id="reply-comment-form-{{$comment->id}}" @if(Request::has('reply')) @if($comment->id != Request::get('reply')) style="display: none;" @endif @else style="display: none;" @endif>

                        @if(!$isBannedIp)
                            @if(Auth::check())
                                @if(!Auth::user()->is_banned)
                                    @if(!Auth::user()->is_agree)
                                        @include('messages.rulesAgreeForComments', ['backUrl' => Request::url() . '?reply=' . $comment->id . '#comment-' . $comment->id])
                                    @else
                                        @if(Request::has('reply'))
                                            @if($comment->id == Request::get('reply'))
                                                <!-- всплывающее сообщение - согласие с правилами сайта -->
                                                @if(Session::has('rulesSuccessMessage'))
                                                    @section('siteMessages')
                                                        @include('widgets.siteMessages.success', ['siteMessage' => Session::get('rulesSuccessMessage')])
                                                        @parent
                                                    @stop
                                                @endif
                                            @endif
                                        @endif

                                        {{ Form::open([
                                              'action' => ['CommentsController@addComment', $page->id],
                                              'id' => 'comment-form-' . $comment->id,
                                            ])
                                        }}

                                        {{ Form::hidden('parent_id', $comment->id); }}

                                        <a href="{{ URL::route('user.profile', ['login' => Auth::user()->getLoginForUrl()]) }}" class="login pull-left">
                                            <span>{{  Auth::user()->login }}</span>
                                        </a>

                                        <div class="clearfix"></div>

                                        <div class="form-group">
                                            {{ Form::textarea('comment', '', ['class' => 'form-control editor-light', 'id' => 'comment-textarea-' . $comment->id, 'data-parent-comment-id' => $comment->id , 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                                            <div class="comment_error error text-danger"></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 col-md-offset-8 col-sm-4 col-sm-offset-8 col-xs-12 col-xs-offset-0">
                                                {{ Form::submit('Отправить', ['id'=> 'submit-' . $comment->id, 'class' => 'btn btn-success btn-sm btn-full pull-right']) }}
                                            </div>
                                        </div>

                                        {{ Form::hidden('_token', csrf_token()) }}
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
                                    {{ Form::textarea('comment', '', ['class' => 'form-control editor-light', 'id' => 'comment-textarea-' . $comment->id, 'data-parent-comment-id' => $comment->id , 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                                    <div class="comment_error error text-danger"></div>
                                </div>

                                <!-- captcha -->
                                <div id="recaptcha-{{ $comment->id }}" class="captcha"></div>
                                @section('captcha')
                                    @parent
                                        var recaptcha<?php echo $comment->id ?> = grecaptcha.render('recaptcha-<?php echo $comment->id ?>', {
                                        'sitekey' : '<?php echo Config::get('settings.nocaptchaSitekey') ?>', //Replace this with your Site key
                                        'theme' : 'light'
                                        });
                                @stop
                            <div class="g-recaptcha-response_error error text-danger"></div>

                            <div class="row">
                                <div class="col-md-4 col-md-offset-8 col-sm-4 col-sm-offset-8 col-xs-12 col-xs-offset-0">
                                    {{ Form::submit('Отправить', ['id'=> 'submit-' . $comment->id, 'class' => 'btn btn-success btn-sm btn-full pull-right']) }}
                                </div>
                            </div>

                            {{ Form::hidden('_token', csrf_token()) }}
                            {{ Form::close() }}
                        @endif
                        @else
                            @include('messages.bannedIp')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="comment-text @if($comment->mark == Comment::MARK_BEST) best @endif @if($comment->is_deleted) deleted @endif">
            Комментарий удален.
        </div>
    @endif


    <!-- Nested Comment -->
    <div class="children-comments">
        @foreach($comment->publishedChildren as $commentLevel2)
            @include('widgets.comment.comment2Level', ['page' => $page, 'commentLevel2' => $commentLevel2, 'isBannedIp' => $isBannedIp])
        @endforeach
    </div>
    <!-- End Nested Comment -->
</div>