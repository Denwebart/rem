<section id="comments-widget">
    <h3>Комментарии ({{ count($page->publishedComments) }})</h3>

    <div class="comments">
        <hr class="small">

        <!-- First level comment -->
        <ul>
            @foreach($comments as $comment)
                <li id="comment-{{ $comment->id }}">
                    <a href="javascript:void(0)" class="pull-left close-comment">-</a>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="thumb">
                                <img src="/images/fire-fox-ava.jpg" alt="commenter" class="img-responsive">
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div>
                                {{ $comment->comment }}
                            </div>
                            <p>
                                <a href="" class="author">{{ $comment->user->login }}</a>
                                <span>{{ $comment->created_at }}</span>
                                <a href="javascript:void(0)" class="reply" data-comment-id="{{ $comment->id }}">Ответить</a>
                            </p>
                        </div>
                        <div class="col-sm-2">
                            <div class="pull-right">
                                <a href="#"><span class="glyphicon glyphicon-arrow-up"></span></a>
                                <br/>
                                <span>2</span>
                                <br/>
                                <a href=""><span class="glyphicon glyphicon-arrow-down"></span></a>
                            </div>
                        </div>
                    </div>

                    <div class="reply-comment-form" id="reply-comment-form-{{$comment->id}}" style="display: none;">

                        @if(Auth::check())
                            <div id="successMessage"></div>

                            {{ Form::open([
                                  'action' => ['CommentsController@addComment', $page->id],
                                  'id' => 'comment-form-' . $comment->id,
                                ])
                            }}

                            {{ Form::hidden('parent_id', $comment->id); }}

                            {{ Auth::user()->login }}
                            <div class="comment-input">
                                {{ Form::textarea('comment', '', ['class' => 'form-input', 'placeholder' => 'Комментарий*']); }}
                                <div id ="comment_error"></div>
                            </div>

                            {{ Form::submit('Отправить', ['id'=> 'submit-' . $comment->id, 'class' => 'btn btn-prime btn-mid']) }}

                            {{ Form::close() }}
                        @else
                            Зарегистрируйтесь или войдите
                            {{ HTML::link(URL::to('users/login'), 'Вход') }}
                            {{ HTML::link(URL::to('users/register'), 'Регистрация') }}
                        @endif
                    </div>

                    <hr class="small">
                    @if(count($comment->publishedChildren))
                        <ul class="children-comments">
                            @foreach($comment->publishedChildren as $commentLevel2)
                                <li>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="thumb">
                                                <img src="/images/ava_cat.jpg" alt="commenter" class="img-responsive">
                                            </div>
                                        </div>
                                        <div class="col-sm-20">
                                            <div>
                                                {{ $commentLevel2->comment }}
                                            </div>
                                            <p>
                                                <a href="" class="author">{{ $commentLevel2->login }}</a>
                                                <span>{{ $commentLevel2->created_at }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="small">
                                </li>
                            @endforeach
                        </ul>
                    @endif

                </li>
            @endforeach
        </ul>
    </div>
    <!-- end of .comments -->

    <div class="comment-form">
        <h3>Оставить комментарий</h3>

        @if(Auth::check())
            <div id="successMessage"></div>

            {{ Form::open([
                  'action' => ['CommentsController@addComment', $page->id],
                  'id' => 'comment-form-0',
                ])
            }}

            {{ Form::hidden('parent_id', 0); }}

            {{ Auth::user()->login }}
            <div class="comment-input">
                {{ Form::textarea('comment', '', ['class' => 'form-input', 'placeholder' => 'Комментарий*']); }}
                <div id="comment_error"></div>
            </div>

            {{ Form::submit('Отправить', ['id'=> 'submit-0', 'class' => 'btn btn-prime btn-mid']) }}

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
                url = $form.attr("action");
            var posting = $.post(url, { formData: data });
            posting.done(function(data) {
                if(data.fail) {
                    $.each(data.errors, function(index, value) {
                        var errorDiv = '#'+index+'_error';
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
        $(".reply").on('click', function() {
            var formContainer = "#reply-comment-form-" + $(this).data('commentId');
            if ($(formContainer).is(':visible')) {
                $(formContainer).slideUp();
            } else {
                $(formContainer).slideDown();
            }
        });

        // Сворачивание/разворачивание дочерних комментариев
        $(".close-comment").on('click', function() {
            var commentsContainer = $(this).parent();
            var childrenCommentsContainer = $(commentsContainer).find('.children-comments');
            if ($(childrenCommentsContainer).is(':visible')) {
                $(childrenCommentsContainer).slideUp();
            } else {
                $(childrenCommentsContainer).slideDown();
            }
        });
    </script>
@stop