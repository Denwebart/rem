<section id="comments-widget">
    <h3>{{ $title }} ({{ count($page->publishedComments) }})</h3>

    <div class="comments">

        @foreach($comments as $comment)
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
                        <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-arrow-down"></span></a>
                        <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
                        <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-arrow-up"></span></a>
                        <div class="vote-message"></div>
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
                                            <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-arrow-down"></span></a>
                                            <span class="vote-result">{{ $commentLevel2->votes_like - $commentLevel2->votes_dislike }}</span>
                                            <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-arrow-up"></span></a>
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

    </div>
    <!-- end of .comments -->

    <div class="comment-form">
        <h3>{{ $formTitle }}</h3>

        @if(Auth::check())

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

        @else
            Зарегистрируйтесь или войдите
            {{ HTML::link(URL::to('users/login'), 'Вход') }}
            {{ HTML::link(URL::to('users/register'), 'Регистрация') }}
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
            {name: 'image', items: ['Image']}
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
                        CKEDITOR.instances[instance].setData('');
                    }
                } //success
            }); //done
        });

        // Раскрытие формы для ответа на комментарий
        $('.reply').on('click', function() {
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
    </script>
@stop

