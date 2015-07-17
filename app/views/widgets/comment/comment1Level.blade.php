<?php/*
    require_once base_path() . '/vendor/autoload.php';
    use Anhskohbo\NoCaptcha\NoCaptcha;*/
?>
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
            <div class="mark-comment pull-right" data-mark-comment-id="{{ $comment->id }}">
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

        <div class="reply-comment-form" id="reply-comment-form-{{$comment->id}}" @if(Request::has('reply')) @if($comment->id != Request::get('reply')) style="display: none;" @endif @else style="display: none;" @endif>

            @if(!Ip::isBanned())
                @if(Auth::check())
                    @if(!Auth::user()->is_banned)
                        @if(!Auth::user()->is_agree)
                            @include('messages.rulesAgreeForComments', ['backUrl' => Request::url() . '?reply=' . $comment->id . '#comment-' . $comment->id])
                        @else

                            @if(Request::has('reply'))
                                @if($comment->id == Request::get('reply'))
                                    @if(Session::has('rulesSuccessMessage'))
                                        <div class="alert alert-dismissable alert-success">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            {{ Session::get('rulesSuccessMessage') }}
                                        </div>
                                    @endif
                                @endif
                            @endif

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

{{--                        {{ Form::captcha(['id' => 'captcha-' . $comment->id]) }}--}}
                        <?php
                            // $captcha1 = new NoCaptcha(Config::get('settings.nocaptchaSecret'), Config::get('settings.nocaptchaSitekey'));
                        ?>
                        <div id="recaptcha-{{ $comment->id }}" class="captcha"></div>
                        @section('captcha')
                            @parent
                                var recaptcha<?php echo $comment->id ?> = grecaptcha.render('recaptcha-<?php echo $comment->id ?>', {
                                    'sitekey' : '<?php echo Config::get('settings.nocaptchaSitekey') ?>', //Replace this with your Site key
                                    'theme' : 'light',
                                    'callback' : Recaptcha.focus_response_field
                                });
                        @endsection

                        {{--<script src="https://www.google.com/recaptcha/api.js" async defer></script>--}}
                        {{--<div class="g-recaptcha" data-sitekey="{{ Config::get('settings.nocaptchaSitekey') }}"></div>--}}

{{--                        {{ $captcha1->display(['id' => 'captcha-' . $comment->id]) }}--}}
                        @if ($errors->has('g-recaptcha-response'))
                            <p class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                        @endif

                        {{ Form::submit('Отправить', ['id'=> 'submit-' . $comment->id, 'class' => 'btn btn-prime btn-mid']) }}
                        {{ Form::hidden('_token', csrf_token()) }}
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