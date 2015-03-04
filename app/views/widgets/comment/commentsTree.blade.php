<section id="comments-widget">
    <h3>Комментарии ({{ count($page->publishedComments) }})</h3>

    <div class="comments">

        @foreach($comments as $comment)
            <!-- Comment -->
            <div id="comment-{{ $comment->id }}" class="media">
                <a href="javascript:void(0)" class="pull-left close-comment">-</a>
                <a class="pull-left" href="{{ URL::route('user.profile', ['login' => $comment->user->login]) }}">
                    {{ HTML::image(Config::get('settings.defaultAvatar'), $comment->user->login, ['class' => 'media-object avatar-default', 'width' => '60px']) }}
                </a>
                <div class="media-body">
                    <h4 class="media-heading">
                        <a href="{{ URL::route('user.profile', ['login' => $comment->user->login]) }}" class="author">{{ $comment->user->login }}</a>
                        <small>{{ DateHelper::dateFormat($comment->created_at) }}</small>
                    </h4>
                    <div>{{ $comment->comment }}</div>

                    <div class="pull-right">
                        <a href="#"><span class="glyphicon glyphicon-arrow-up"></span></a>
                        <span class="result">2</span>
                        <a href=""><span class="glyphicon glyphicon-arrow-down"></span></a>
                    </div>

                    <a href="javascript:void(0)" class="reply" data-comment-id="{{ $comment->id }}">Ответить</a>

                    <div class="reply-comment-form well" id="reply-comment-form-{{$comment->id}}" style="display: none;">

                        @if(Auth::check())
                            <div id="successMessage"></div>

                            {{ Form::open([
                                  'action' => ['CommentsController@addComment', $page->id],
                                  'id' => 'comment-form-' . $comment->id,
                                ])
                            }}

                            {{ Form::hidden('parent_id', $comment->id); }}

                            <a href="{{ URL::route('user.profile', ['login' => Auth::user()->login]) }}">
                                {{ HTML::image(Config::get('settings.defaultAvatar'), $comment->user->login, ['class' => 'media-object avatar-default', 'width' => '50px']) }}
                                <span>{{  Auth::user()->login }}</span>
                            </a>

                            <div class="form-group">
                                {{ Form::textarea('comment', '', ['class' => 'form-control', 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                                <div id="comment_error"></div>
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
                                    <a class="pull-left" href="{{ URL::route('user.profile', ['login' => $commentLevel2->user->login]) }}">
                                        {{ HTML::image(Config::get('settings.defaultAvatar'), $commentLevel2->user->login, ['class' => 'media-object avatar-default', 'width' => '50px']) }}
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="{{ URL::route('user.profile', ['login' => $commentLevel2->user->login]) }}" class="author">{{ $commentLevel2->user->login }}</a>
                                            <small>{{ DateHelper::dateFormat($commentLevel2->created_at) }}</small>
                                        </h4>
                                        <div>{{ $commentLevel2->comment }}</div>

                                        <div class="pull-right">
                                            <a href="#"><span class="glyphicon glyphicon-arrow-up"></span></a>
                                            <span class="result">2</span>
                                            <a href=""><span class="glyphicon glyphicon-arrow-down"></span></a>
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

    <div class="comment-form well">
        <h3>Оставить комментарий</h3>

        @if(Auth::check())
            <div id="successMessage"></div>

            {{ Form::open([
                  'action' => ['CommentsController@addComment', $page->id],
                  'id' => 'comment-form-0',
                ])
            }}

            {{ Form::hidden('parent_id', 0); }}

            <a href="{{ URL::route('user.profile', ['login' => Auth::user()->login]) }}">
                {{ HTML::image(Config::get('settings.defaultAvatar'), $comment->user->login, ['class' => 'media-object avatar-default', 'width' => '50px']) }}
                <span>{{  Auth::user()->login }}</span>
            </a>

            <div class="form-group">
                {{ Form::textarea('comment', '', ['class' => 'form-control', 'placeholder' => 'Комментарий*', 'rows' => 3]); }}
                <div id="comment_error"></div>
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

    <script type="text/javascript">

        $("form[id^='comment-form']").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                data = $form.serialize(),
                url = $form.attr('action');
            var posting = $.post(url, { formData: data });
            posting.done(function(data) {
                if(data.fail) {
                    $.each(data.errors, function(index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).addClass('required');
                        $(errorDiv).empty().append(value);
                    });
                    $('#successMessage').empty();
                }
                if(data.success) {
                    var successContent = '<div class="message"><h3>Ваш комментарий успешно отправлен!</h3></div>';
                    $('#successMessage').html(successContent);
                    $($form).trigger('reset');
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
    </script>
@stop