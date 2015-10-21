<section id="comments-widget" class="margin-top-20">
    {{-- Лучшие --}}
    @if(Page::TYPE_QUESTION == $page->type)
        <div id="best-comments" {{ !count($page->bestComments) ? 'style="display: none"' : '' }} class="margin-top-20">
            @include('widgets.comment.bestComments', ['isBannedIp' => $isBannedIp])
        </div>
    @endif

    {{-- Все, кроме лучших --}}
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

    <div id="comments-area" class="margin-top-20">
        <div class="count">
            Показано комментариев: <span>{{ $comments->count() }}</span>.
            Всего: <span>{{ $comments->getTotal() }}</span>.
        </div>
        <div class="comments">
            @foreach($comments as $comment)
                <!-- Comment -->
                @include('widgets.comment.comment1Level', ['page' => $page, 'comment' => $comment, 'isBannedIp' => $isBannedIp])
            @endforeach
        </div>
        {{ $comments->links() }}
    </div>
    <!-- end of .comments -->
    <div class="comment-form margin-top-20" id="add-comment">
        <h3>{{ $formTitle }}</h3>

        @if(!$isBannedIp)
            @if(Auth::check())
                @if(!Auth::user()->is_banned)
                    @if(!Auth::user()->is_agree)
                        @include('messages.rulesAgreeForComments', ['type' => $page->type, 'backUrl' => Request::url() . '#add-comment'])
                    @else

                        <!-- всплывающее сообщение - согласие с правилами сайта -->
                        @if(Session::has('rulesSuccessMessage') && !Request::has('reply'))
                            @section('siteMessages')
                                @include('widgets.siteMessages.success', ['siteMessage' => Session::get('rulesSuccessMessage')])
                                @parent
                            @stop
                        @endif

                        {{ Form::open([
                              'action' => ['CommentsController@addComment', $page->id],
                              'id' => 'comment-form-0',
                            ])
                        }}

                            {{ Form::hidden('parent_id', 0); }}

                            <a href="{{ URL::route('user.profile', ['login' => Auth::user()->getLoginForUrl()]) }}" class="login pull-left">
                                <span>{{  Auth::user()->login }}</span>
                            </a>

                            <div class="clearfix"></div>

                            <div class="form-group">
                                {{ Form::textarea('comment', '', ['class' => 'form-control editor', 'id' => 'comment-textarea-0', 'data-parent-comment-id' => '0', 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                                <div class="comment_error error text-danger"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-md-offset-8 col-sm-4 col-sm-offset-8 col-xs-12 col-xs-offset-0">
                                    {{ Form::submit('Отправить', ['id'=> 'submit-0', 'class' => 'btn btn-success btn-sm btn-full pull-right']) }}
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
                      'id' => 'comment-form-0',
                    ])
                }}

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
                        {{ Form::textarea('comment', '', ['class' => 'form-control editor', 'id' => 'comment-textarea-0', 'data-parent-comment-id' => '0', 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                        <div class="comment_error error text-danger"></div>
                    </div>

                    <!-- captcha -->
                    <div id="recaptcha-0" class="captcha"></div>
                    @section('captcha')
                        @parent
                            var recaptcha0 = grecaptcha.render('recaptcha-0', {
                                'sitekey' : '<?php echo Config::get('settings.nocaptchaSitekey') ?>',
                                'theme' : 'light'
                            });
                    @stop
                    <div class="g-recaptcha-response_error error text-danger"></div>

                    {{ Form::hidden('_token', csrf_token()) }}

                    <div class="row">
                        <div class="col-md-4 col-md-offset-8 col-sm-4 col-sm-offset-8 col-xs-12 col-xs-offset-0">
                            {{ Form::submit('Отправить', ['id'=> 'submit-0', 'class' => 'btn btn-success btn-sm btn-full pull-right']) }}
                        </div>
                    </div>

                {{ Form::close() }}
            @endif
        @else
            @include('messages.bannedIp')
        @endif

        <!-- TinyMCE image -->
        {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
        {{ Form::hidden('tempPath', '/uploads/temp/' . Str::random(20) . '/', ['id' => 'tempPath']) }}

    </div>
    <!-- end of .comment-form -->
</section> <!-- end of .comments-area -->

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init', [
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
        $("#comments").on('submit', "form[id^='comment-form']", function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var parentCommentId = $(this).find('textarea').data('parentCommentId');
            if(tinyMCE.get("comment-textarea-" + parentCommentId))
            {
                tinyMCE.get("comment-textarea-" + parentCommentId).save();
            }
            var $form = $(this),
                data = $form.serialize(),
                url = $form.attr('action');
            $.ajax({
                url: url,
                dataType: "text json",
                type: "POST",
                async: true,
                data: {formData: data, tempPath: $('#tempPath').val()},
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
                    }
                    if(data.success) {
                        var successContent = '@include('widgets.siteMessages.info', ['siteMessage' => $successMessage])';
                        $('#site-messages').prepend(successContent);
                        $form.trigger('reset');
                        tinyMCE.activeEditor.setContent('');
                        $form.find('.error').empty();

                        // сворачивание формы
                        $('#comment-' + data.parent_id).find("[id^='reply-comment-form']").hide();

                        // вывод комментария
                        if(0 == data.parent_id){
                            $('.comments').prepend(data.commentHtml);
                        } else {
                            $('#comment-' + data.parent_id).find('.children-comments').append(data.commentHtml);
                        }
                        $('.count-comments').text(data.countComments);

                        $('#comment-' + data.parent_id).find('.close-comment').show();
                        $('.mce-floatpanel').hide();
                        // скролл на новый комментарий
                        $('html, body').animate({
                            scrollTop: $('#comment-' + data.comment_id).offset().top - 50
                        }, 1000);

                        // отметить комментарий как новый
                        $('#comment-' + data.comment_id).addClass('new-comment');
                        setTimeout(function() {
                            if(0 == data.parent_id){
                                $('#comment-' + data.comment_id).find('.comment-text')
                                        .css('background', '#F2F2F2')
                                        .css('border-color', '#03A9F4');
                            } else {
                                $('#comment-' + data.comment_id).find('.comment-text')
                                        .css('background', '#FFFFFF');
                            }
                        }, 3000);
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
                $(formContainer).slideDown();
            }
        });

        // Сворачивание/разворачивание дочерних комментариев
        $('.close-comment').on('click', function() {
            var commentsContainer = $(this).parent();
            var childrenCommentsContainer = $(commentsContainer).find('.children-comments');
            if ($(childrenCommentsContainer).is(':visible')) {
                $(childrenCommentsContainer).slideUp();
                $(this).html('<i class="material-icons">keyboard_arrow_down</i>');
                $(this).attr('data-original-title', 'Показать дочерние комментарии');
            } else {
                $(childrenCommentsContainer).slideDown();
                $(this).html('<i class="material-icons">keyboard_arrow_up</i>');
                $(this).attr('data-original-title', 'Скрыть дочерние комментарии');
            }
            $(this).nextAll('.tooltip:first').remove();
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
                        $('#site-messages').prepend(response.message);
                        $markTag.html('<i class="material-icons mdi-success" title="Лучший ответ" data-toggle="tooltip" data-placement="top" style="font-size: 40pt;">done</i>');
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
        $('#comments-widget').on('click', '.vote-like', function() {
            var commentId = $(this).parent().data('voteCommentId');
            $.ajax({
                url: '/comment/vote/' + commentId,
                dataType: "text json",
                type: "POST",
                data: {vote: 'like', 'userLogin': '<?php echo Auth::check() ? Auth::user()->login : '' ?>'},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $('[data-vote-comment-id='+ commentId +']').find('.vote-result').text(response.votesLike - response.votesDislike);
                        $('#site-messages').prepend(response.message);
                    } else {
                        $('#site-messages').prepend(response.message);
                    }
                }
            });
        });
        $('#comments-widget').on('click', '.vote-dislike', function() {
            var commentId = $(this).parent().data('voteCommentId');
            $.ajax({
                url: '/comment/vote/' + commentId,
                dataType: "text json",
                type: "POST",
                data: {vote: 'dislike', 'userLogin': '<?php echo Auth::check() ? Auth::user()->login : '' ?>'},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $('[data-vote-comment-id='+ commentId +']').find('.vote-result').text(response.votesLike - response.votesDislike);
                        $('#site-messages').prepend(response.message);
                    } else {
                        $('#site-messages').prepend(response.message);
                    }
                }
            });
        });

        // Удаление комментария
        $('#comments-widget').on('click', '.delete-comment', function() {
            var commentId = $(this).data('id');
            if(confirm('Вы уверены, что хотите удалить комментарий?')) {
                $.ajax({
                    url: '/admin/comments/ajaxDelete/' + commentId,
                    dataType: "text json",
                    type: "POST",
                    data: {},
                    beforeSend: function (request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function (response) {
                        if (response.success) {
                            $('[id="comment-' + commentId + '"]').remove();
                            $('#site-messages').prepend(response.message);
                        }
                    }
                });
            }
        });

        var selectedText = '';
        function selectQuoteText() {
            if (selectedText = window.getSelection) // Not IE, используем метод getSelection
                selectedText = window.getSelection().toString();
            else // IE, используем объект selection
                selectedText = document.selection.createRange().text;
            selectedText = $.trim(selectedText);

            if(selectedText != '' && selectedText.length > 2) {
                return selectedText;
            } else {
                return false
            }
        }

        $('#comments-widget').on('mouseup', '.children-comments .comment-content', function(e) {
            var commentParentId = $(this).data("id");
            if(selectQuoteText()) {
                $(this).prepend('<a id="add-quote" data-section="comment-level-2" href="javascript:void(0)" data-comment-parent-id="">Цитировать</a>');
            }
        });
        $('#comments-widget').on('mouseup', '.comment-content', function(e) {
            if(selectQuoteText()) {
                $(this).prepend('<a id="add-quote" data-section="comment-level-1" data-comment-parent-id="0" href="javascript:void(0)">Цитировать</a>');
            }
        });
        $('.content').on('mouseup', function(e) {
            if(selectQuoteText()) {
//              $('#popUpBox').css({'display':'block', 'left':e.pageX-60+'px', 'top':e.pageY+5+'px'});
                $(this).prepend('<a id="add-quote" data-section="content" data-comment-parent-id="0" href="javascript:void(0)">Цитировать</a>');
//              $('#add-quote').css({'display':'block', 'left':e.pageX-60+'px', 'top':e.pageY+5+'px'});
            }
        });

        $(document).bind("mousedown", function(){
            $('#add-quote').remove();
        });

        $(document).on("mousedown", '#add-quote', function(){
            var section = $(this).data('section');
            if(section = 'content') {
                console.log(section);
                var value = tinyMCE.get("comment-textarea-0").save();
                value = '<blockquote>'+ selectedText +'</blockquote><br>';
                tinyMCE.get("comment-textarea-0").insertContent(value);

//            $('[name^="comment"]').val(value + '<blockquote>'+ selectedText +'</blockquote>');
//            $('#add-quote').remove();
            } else if(section = 'comment-level-2') {
                console.log(section);
                var commentParentId = $(this).data('commentParentId');
                var value = $('#comment-textarea-' + commentParentId).val();
                value = value + '<br><blockquote>'+ selectedText +'</blockquote><br>';
                $('#comment-textarea-' + commentParentId).val(value);
            } else if(section = 'comment-level-1') {
                console.log(section);
//                var value = tinyMCE.get("comment-textarea-0").save();
//                value = '<blockquote>'+ selectedText +'</blockquote><br>';
//                tinyMCE.get("comment-textarea-0").insertContent(value);

//            $('[name^="comment"]').val(value + '<blockquote>'+ selectedText +'</blockquote>');
//            $('#add-quote').remove();
            }
        });

    </script>
@stop