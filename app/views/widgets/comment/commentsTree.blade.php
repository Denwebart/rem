<section id="comments-widget">
    {{--Лучшие ответы--}}
    @if(Page::TYPE_QUESTION == $page->type)
        <div id="best-comments" {{ !count($page->bestComments) ? 'style="display: none"' : '' }}>
            @include('widgets.comment.bestComments')
        </div>
    @endif

    {{--Ответы (все, кроме лучших)--}}
    <h3>{{ $title }}
        <span class="count-comments">
            ({{ count($page->publishedComments) - count($page->bestComments) }})
        </span>
    </h3>

    <div class="comments">

        {{--Отметить лучшие ответы--}}
        @if(Auth::check() && Page::TYPE_QUESTION == $page->type)
            @if(Auth::user()->is($page->user) && count($comments))
                <div class="clearfix">
                    <a href="javascript:void(0)" class="btn btn-primary btn-raised pull-right" id="mark-as-best">
                        Отметить лучшие ответы
                    </a>
                </div>
            @endif
        @endif

        @foreach($comments as $comment)
            <!-- Comment -->
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
                    </h4>
                    <div>{{ $comment->comment }}</div>

                    <div class="vote pull-right" data-vote-comment-id="{{ $comment->id }}">
                        @if(Auth::check())
                            @if(!Auth::user()->is($comment->user))
                                <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
                                <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
                                <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                                <div class="vote-message"></div>
                            @else
                                <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
                            @endif
                        @else
                            <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
                            <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
                            <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                            <div class="vote-message"></div>
                        @endif
                    </div>

                    {{--Отметить лучшие ответы--}}
                    @if($comment->mark == Comment::MARK_BEST)
                        <div class="mark-comment pull-right" data-mark-comment-id="{{ $comment->id }}">
                            <i class="mdi-action-done mdi-success" style="font-size: 40pt;"></i>
                        </div>
                    @elseif(Auth::check())
                        @if(Auth::user()->is($page->user) && $page->type == Page::TYPE_QUESTION)
                            <div class="mark-comment pull-right" data-mark-comment-id="{{ $comment->id }}">
                                <a href="javascript:void(0)" class="pull-left mark-comment-as-best" style="display:none">
                                    <i class="mdi-action-done mdi-material-grey" style="font-size: 40pt;"></i>
                                </a>
                            </div>
                        @endif
                    @endif

                    <a href="javascript:void(0)" class="reply" data-comment-id="{{ $comment->id }}">Ответить</a>

                    <div class="reply-comment-form" id="reply-comment-form-{{$comment->id}}" style="display: none;">

                        @if(Auth::check())

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
                            {{ Form::open([
                                      'action' => ['CommentsController@addComment', $page->id],
                                      'id' => 'comment-form-' . $comment->id,
                                    ])
                                }}

                            <div class="successMessage"></div>

                            {{ Form::hidden('parent_id', $comment->id); }}

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    {{ Form::text('user_name', '', ['class' => 'form-control', 'placeholder' => 'Имя*']); }}
                                    <div class="user_name_error error text-danger"></div>
                                </div>
                                <div class="col-md-6 form-group">
                                    {{ Form::text('user_email', '', ['class' => 'form-control', 'placeholder' => 'Email*']); }}
                                    <div class="user_email_error error text-danger"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                {{ Form::textarea('comment', '', ['class' => 'form-control editor', 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                                <div class="comment_error error text-danger"></div>
                            </div>

                            {{ Form::submit('Отправить', ['id'=> 'submit-' . $comment->id, 'class' => 'btn btn-prime btn-mid']) }}

                            {{ Form::close() }}
                        @endif
                    </div>

                    <!-- Nested Comment -->
                    @if(count($comment->publishedChildren))
                        <div class="children-comments">
                            @foreach($comment->publishedChildren as $commentLevel2)
                                <div class="media" id="comment-{{ $commentLevel2->id }}" >
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
                                            <small>{{ DateHelper::dateFormat($commentLevel2->created_at) }}</small>
                                        </h4>
                                        <div>{{ $commentLevel2->comment }}</div>

                                        <div class="vote pull-right" data-vote-comment-id="{{ $commentLevel2->id }}">
                                            @if(Auth::check())
                                                @if(!Auth::user()->is($commentLevel2->user))
                                                    <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
                                                    <span class="vote-result">{{ $commentLevel2->votes_like - $commentLevel2->votes_dislike }}</span>
                                                    <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                                                    <div class="vote-message"></div>
                                                @else
                                                    <span class="vote-result">{{ $commentLevel2->votes_like - $commentLevel2->votes_dislike }}</span>
                                                @endif
                                            @else
                                                <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
                                                <span class="vote-result">{{ $commentLevel2->votes_like - $commentLevel2->votes_dislike }}</span>
                                                <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                                                <div class="vote-message"></div>
                                            @endif
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
    </div>
    <!-- end of .comments -->

    <div class="comment-form">
        <h3>{{ $formTitle }}</h3>

        @if(Auth::check())
            @if(!Auth::user()->is_agree)
                <div class="alert alert-dismissable alert-warning">
                    <h4>Вы не согласились с правилами сайта!</h4>
                    <p>Пока вы не соглавитесь с правилами сайта,
                        вы не сможете @if(Page::TYPE_QUESTION == $page->type) отвечать на вопросы. @else оставлять комментарии. @endif
                        Для ознакомления с правилами перейдите по <a href="{{ URL::route('rules', ['rulesAlias' => 'rules', 'backUrl' => urlencode(Request::url())]) }}" class="alert-link">ссылке</a>.</p>
                </div>
            @else

                {{ Form::open([
                      'action' => ['CommentsController@addComment', $page->id],
                      'id' => 'comment-form-0',
                    ])
                }}

                <div class="successMessage"></div>

                {{ Form::hidden('parent_id', 0); }}

                <a href="{{ URL::route('user.profile', ['login' => Auth::user()->getLoginForUrl()]) }}">
                    {{ Auth::user()->getAvatar('mini', ['class' => 'media-object']) }}
                    <span>{{  Auth::user()->login }}</span>
                </a>

                <div class="form-group">
                    {{ Form::textarea('comment', '', ['class' => 'form-control editor', 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                    <div class="comment_error error text-danger"></div>
                </div>

                {{ Form::submit('Отправить', ['id'=> 'submit-0', 'class' => 'btn btn-primary']) }}

                {{ Form::close() }}
            @endif
        @else
            {{ Form::open([
                  'action' => ['CommentsController@addComment', $page->id],
                  'id' => 'comment-form-0',
                ])
            }}

            <div class="successMessage"></div>

            {{ Form::hidden('parent_id', 0); }}

            <div class="row">
                <div class="col-md-6 form-group">
                    {{ Form::text('user_name', '', ['class' => 'form-control', 'placeholder' => 'Имя*']); }}
                    <div class="user_name_error error text-danger"></div>
                </div>
                <div class="col-md-6 form-group">
                    {{ Form::text('user_email', '', ['class' => 'form-control', 'placeholder' => 'Email*']); }}
                    <div class="user_email_error error text-danger"></div>
                </div>
            </div>

            <div class="form-group">
                {{ Form::textarea('comment', '', ['class' => 'form-control editor', 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                <div class="comment_error error text-danger"></div>
            </div>

            {{ Form::submit('Отправить', ['id'=> 'submit-0', 'class' => 'btn btn-prime btn-mid']) }}

            {{ Form::close() }}
        @endif
    </div>
    <!-- end of .comment-form -->
</section> <!-- end of .comments-area -->

@section('script')
    @parent

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.config.toolbar = [
            {name: 'paragraph', items: ['NumberedList', 'BulletedList']},
            {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike']},
            {name: 'links', items: ['Link', 'Unlink']},
            {name: 'image', items: ['Image']},
            { name: 'smiley', items: ['Smiley']}
        ];
        CKEDITOR.replaceAll('editor');

    </script>


    <script type="text/javascript">

        $("form[id^='comment-form']").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            var $form = $(this),
                data = $form.serialize(),
                url = $form.attr('action');
            var posting = $.post(url, { formData: data });
            posting.done(function(data) {
                if(data.fail) {
                    $.each(data.errors, function(index, value) {
                        var errorDiv = '.' + index + '_error';
                        $form.find(errorDiv).parent().addClass('has-error');
                        $form.find(errorDiv).empty().append(value);
                    });
                    $form.find('.successMessage').empty();
                }
                if(data.success) {
                    var successContent = '<div class="message"><h3>{{ $successMessage }}</h3></div>';
                    $form.find('.successMessage').html(successContent);
                    $form.trigger('reset');
                    $form.find('.error').empty();
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData('');
                    }
                    // вывод комментария
                    if(0 == data.parent_id){
                        $('.comments').append(data.commentHtml);
                    } else {
                        $('#comment-' + data.parent_id).find('.children-comments').append(data.commentHtml);
                    }

                } //success
            }); //done
        });

        // Раскрытие формы для ответа на комментарий
        $('.comments').on('click', '.reply', function() {
            var formContainer = '#reply-comment-form-' + $(this).data('commentId');
            if ($(formContainer).is(':visible')) {
                $(formContainer).slideUp();
            } else {
                $("[id^='reply-comment-form']").slideUp();
                $(formContainer).slideDown();
            }
        });

        // Сворачивание/разворачивание дочерних комментариев
        $('.close-comment').on('click', function() {
            var commentsContainer = $(this).parent();
            var childrenCommentsContainer = $(commentsContainer).find('.children-comments');
            if ($(childrenCommentsContainer).is(':visible')) {
                $(childrenCommentsContainer).slideUp();
                $(this).text('+');
            } else {
                $(childrenCommentsContainer).slideDown();
                $(this).text('-');
            }
        });

        // Отметить комментарий как лучший
        $('#mark-as-best').on('click', function() {
            if($(this).hasClass('btn-primary')) {
                $('.mark-comment-as-best').show();
                $(this).text('Отмена');
            } else {
                $('.mark-comment-as-best').hide();
                $(this).text('Отметить лучший ответ');
            }
            $(this).toggleClass("btn-primary btn-default");
        });
        $(".mark-comment").on('click', '.mark-comment-as-best', function() {
            var $markTag = $(this).parent();
            var commentId = $(this).parent().data('markCommentId');
            $.ajax({
                url: '/comment/mark/' + commentId,
                dataType: "text json",
                type: "POST",
                data: {mark: '<?php echo Comment::MARK_BEST ?>'},
                success: function(response) {
                    if(response.success){
                        $markTag.html('<i class="mdi-action-done mdi-success" style="font-size: 40pt;"></i>');
                        $markTag.append('<div class="message">' + response.message + '</div>');
                        $('#comment-' + commentId).remove();
                        $('#best-comments').show();
                        $('#best-comments').html(response.bestCommentsHtml);
                        $('.count-comments').text('(' + response.countComments + ')');
                        $('.count-best-comments').text('(' + response.countBestComments + ')');
                        if(0 == response.countComments) {
                            $('#mark-as-best').remove();
                        }
                    }
                }
            });
        });

        // Голосование за комментарий
        $(".vote-like").on('click', function() {
            var commentId = $(this).parent().data('voteCommentId');
            $.ajax({
                url: '/comment/vote/' + commentId,
                dataType: "text json",
                type: "POST",
                data: {vote: 'like'},
                success: function(response) {
                    if(response.success){
                        $('[data-vote-comment-id='+ commentId +']').find('.vote-result').text(response.votesLike - response.votesDislike);
                        $('[data-vote-comment-id='+ commentId +']').find('.vote-message').text(response.message);
                    } else {
                        $('[data-vote-comment-id='+ commentId +']').find('.vote-message').text(response.message);
                    }
                }
            });
        });
        $(".vote-dislike").on('click', function() {
            var commentId = $(this).parent().data('voteCommentId');
            $.ajax({
                url: '/comment/vote/' + commentId,
                dataType: "text json",
                type: "POST",
                data: {vote: 'dislike'},
                success: function(response) {
                    if(response.success){
                        $('[data-vote-comment-id='+ commentId +']').find('.vote-result').text(response.votesLike - response.votesDislike);
                        $('[data-vote-comment-id='+ commentId +']').find('.vote-message').text(response.message);
                    } else {
                        $('[data-vote-comment-id='+ commentId +']').find('.vote-message').text(response.message);
                    }
                }
            });
        });
    </script>
@stop

