<?php
        /*
    require_once base_path() . '/vendor/autoload.php';
    use Anhskohbo\NoCaptcha\NoCaptcha;
        */
?>
<section id="comments-widget">
    {{--Лучшие ответы--}}
    @if(Page::TYPE_QUESTION == $page->type)
        <div id="best-comments" {{ !count($page->bestComments) ? 'style="display: none"' : '' }}>
            @include('widgets.comment.bestComments', ['isBannedIp' => $isBannedIp])
        </div>
    @endif

    {{--Ответы (все, кроме лучших)--}}
    <h3>{{ $title }}
        @if(Page::TYPE_QUESTION == $page->type)
            <span class="count-comments">
                (
                <span class="count-comments">
                    {{ count($page->publishedAnswers) - count($page->bestComments) }}
                </span>
                )
            </span>
        @else
            <span>
                (
                <span class="count-comments">
                    {{ count($page->publishedComments) }}
                </span>
                )
            </span>
        @endif
    </h3>

    <div class="comments">
        @foreach($comments as $comment)
            <!-- Comment -->
            @include('widgets.comment.comment1Level', ['page' => $page, 'comment' => $comment, 'isBannedIp' => $isBannedIp])
        @endforeach
    </div>
    <!-- end of .comments -->

    <div class="comment-form" id="add-comment">
        <h3>{{ $formTitle }}</h3>

        @if(!$isBannedIp)
            @if(Auth::check())
                @if(!Auth::user()->is_banned)
                    @if(!Auth::user()->is_agree)
                        @include('messages.rulesAgreeForComments', ['type' => $page->type, 'backUrl' => Request::url() . '#add-comment'])
                    @else

                        @if(Session::has('rulesSuccessMessage') && !Request::has('reply'))
                            <div class="alert alert-dismissable alert-success">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{ Session::get('rulesSuccessMessage') }}
                            </div>
                        @endif

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
                            {{ Form::hidden('_token', csrf_token()) }}
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

                    <!-- captcha -->
                    <div id="recaptcha-0" class="captcha"></div>
                    @section('captcha')
                        @parent
                            var recaptcha0 = grecaptcha.render('recaptcha-0', {
                                'sitekey' : '<?php echo Config::get('settings.nocaptchaSitekey') ?>', //Replace this with your Site key
                                'theme' : 'light'
                            });
                    @endsection
                    <div class="g-recaptcha-response_error error text-danger"></div>

                    {{ Form::hidden('_token', csrf_token()) }}

                    {{ Form::submit('Отправить', ['id'=> 'submit-0', 'class' => 'btn btn-prime btn-mid']) }}
                {{ Form::close() }}
            @endif
        @else
            @include('messages.bannedIp')
        @endif

        <!-- TinyMCE image -->
        {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}

    </div>
    <!-- end of .comment-form -->
</section> <!-- end of .comments-area -->

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init', [
        'imagePath' => $page->getCommentImagePath(),
        'toolbar' => 'bold italic | bullist numlist | link image media emoticons | print preview'
    ])
@stop

@section('script')
    @parent

    <!-- captcha -->
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCaptcha&render=explicit" async defer></script>
    <script>
        var onloadCaptcha = function() {
           @yield('captcha')
        };
    </script>
    <!-- captcha -->

    <script type="text/javascript">

        // Отправка комментария
        $("form[id^='comment-form']").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                data = $form.serialize(),
                url = $form.attr('action');
            $.ajax({
                url: url,
                dataType: "text json",
                type: "POST",
                async: true,
                data: {formData: data},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(data) {
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
                        // вывод комментария
                        if(0 == data.parent_id){
                            $('.comments').append(data.commentHtml);
                        } else {
                            $('#comment-' + data.parent_id).find('.children-comments').append(data.commentHtml);
                        }
                        $('.count-comments').text(data.countComments);

                    } //success
                }
            });

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
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $markTag.html('<i class="material-icons mdi-success" title="Лучший ответ" style="font-size: 40pt;">done</i>');
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
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
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
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
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