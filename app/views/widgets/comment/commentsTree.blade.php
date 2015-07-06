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
            ({{ count($page->publishedAnswers) - count($page->bestComments) }})
        </span>
    </h3>

    <div class="comments">
        @foreach($comments as $comment)
            <!-- Comment -->
            @include('widgets.comment.comment1Level', ['page' => $page, 'comment' => $comment])
        @endforeach
    </div>
    <!-- end of .comments -->

    <div class="comment-form">
        <h3>{{ $formTitle }}</h3>

        @if(!Ip::isBanned())
            @if(Auth::check())
                @if(!Auth::user()->is_banned)
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
                    @include('cabinet::user.banMessage')
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

                {{ Form::submit('Отправить', ['id'=> 'submit-0', 'class' => 'btn btn-prime btn-mid']) }}

                {{ Form::close() }}
            @endif
        @else
            @include('messages.bannedIp')
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
                    var successContent = '<div class="alert alert-dismissable alert-info">' +
                            '<button type="button" class="close" data-dismiss="alert">×</button>' +
                            '{{ $successMessage }}' +
                            '</div>';
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
        $('.comments, #best-comments').on('click', '.reply', function() {
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