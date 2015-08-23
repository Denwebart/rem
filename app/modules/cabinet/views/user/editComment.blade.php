@extends('cabinet::layouts.cabinet')

<?php
$title = ($comment->is_answer) ? 'Редактировать ответ' : 'Редактировать комментарий';
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3">
        @include('cabinet::user.userInfo')
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
            <li>
                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                    {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                </a>
            </li>
            @if($comment->is_answer)
                <li>
                    <a href="{{ URL::route('user.answers', ['login' => $user->getLoginForUrl()]) }}">Мои ответы</a>
                </li>
            @else
                <li>
                    <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}">Мои комментарии</a>
                </li>
            @endif
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12 content">
                <div id="form-area">
                    <h2>{{ $title }}</h2>
                    <div class="well">
                        <div class="row">
                            {{ Form::model($comment, ['method' => 'PUT', 'route' => ['user.comments.update', 'login' => $user->getLoginForUrl(), 'id' => $comment->id], 'id' => 'comment-form', 'files' => true]) }}

                            <div class="col-md-12">
                                <div class="pull-right margin-bottom-20">
                                    <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}" class="btn btn-primary btn-sm">
                                        <i class="material-icons">keyboard_arrow_left</i>
                                        Отмена
                                    </a>
                                    {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm']) }}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('comment', ($comment->is_answer) ? 'Текст ответа' : 'Текст комментария') }}
                                    {{ Form::textarea('comment', $comment->comment, ['class' => 'form-control editor']) }}
                                    {{ $errors->first('comment') }}
                                </div>

                                <!-- TinyMCE image -->
                                {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
                            </div>

                            {{ Form::hidden('_token', csrf_token()) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div id="preview" style="display: none"></div>
            </div>
        </div>
    </div>
@stop

@section('style')
    @parent
    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
    <script src="/js/jquery-ui.min.js"></script>

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init', ['imagePath' => $comment->getImageEditorPath()])
@endsection

@section('script')
    @parent

    <script type="text/javascript">
        $(document).ready(function() {
            // Теги
            var tagNumber = 0;
            // убираем ошибку при изменении поля
            $('#tag-input').on('focus', function(){
                $(this).parent().find('.error').hide().text('');
                $(this).parent().removeClass('has-error');
            });
            // автокомплит при добавлении тега
            $("#tag-input").autocomplete({
                source: "<?php echo URL::route('tagAutocomplete') ?>",
                minLength: 1,
                select: function(e, ui) {
                    addTag(ui.item.id, ui.item.value);
                    $(this).autocomplete('close');
                    $(this).val() = "";
                    return false;
                }
            });
            // добавление тега
            $('.add-tag').on('click', function() {
                var addedTagTitle = $('#tags-area').find('.add-tag-input input').val();
                if(addedTagTitle.trim() != '') {
                    addTag(0, addedTagTitle);
                    tagNumber++;
                } else {
                    $('.add-tag-input .error').show().text('Нельзя добавить пустой тег.');
                    $('.add-tag-input .form-group').addClass('has-error');
                }
            });

            function addTag(addedTagId, addedTagTitle) {
                var $tagBlock = $('#tags-area');

                var aTags = $tagBlock.find('.tag-title');
                var found;
                for (var i = 0; i < aTags.length; i++) {
                    if (aTags[i].textContent.toLowerCase() == addedTagTitle.toLowerCase()) {
                        found = aTags[i];
                        break;
                    }
                }
                if(found) {
                    $('.add-tag-input .error').show().text('Такой тег уже добавлен.');
                    $('.add-tag-input .form-group').addClass('has-error');
                } else {
                    var addedTagInputName = (0 != addedTagId)
                            ? 'tags['+ addedTagId +']'
                            : 'tags[newTags]['+ tagNumber +']';
                    var html = '<div class="btn-group tag" data-id="'+ addedTagId +'">' +
                            '<input name="'+ addedTagInputName +'" value="'+ addedTagTitle +'" type="hidden">' +
                            '<a href="javascript:void(0)" class="btn btn-info btn-sm tag-title">'+ addedTagTitle +'</a>' +
                            '<a href="javascript:void(0)" class="btn btn-danger btn-sm remove-tag">' +
                            '<i class="material-icons">close</i>' +
                            '</a></div>';

                    $tagBlock.find('.tags').append(html);
                    $('#tag-input').val('');
                    $tagBlock.find('.show-add-tag').toggleClass('btn-info btn-warning').html('<i class="material-icons">close</i>');
                }
            }

            // удаление тега
            $('.tags').on('click', '.remove-tag', function() {
                $(this).parent().remove();
            });
        });
    </script>

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();

        $(".file-inputs").on("change", function(){
            var file = this.files[0];
            if (file.size > 5242880) {
                $(this).parent().parent().append('Недопустимый размер файла.');
            }
        });
    </script>

@stop