@extends('cabinet::layouts.cabinet')

<?php
$title = ($comment->is_answer) ? 'Редактировать ответ' : 'Редактировать комментарий';
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
        @include('cabinet::user.userInfo')
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        @include('widgets.breadcrumbs', ['items' => [
            [
                'title' => Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login,
                'url' => URL::route('user.profile', ['login' => $user->getLoginForUrl()])
            ],
            [
                'title' => ($comment->is_answer) ? 'Мои ответы' : 'Мои комментарии',
                'url' => ($comment->is_answer)
                    ? URL::route('user.answers', ['login' => $user->getLoginForUrl()])
                    : URL::route('user.comments', ['login' => $user->getLoginForUrl()])
            ],
            [
                'title' => $title
            ]
        ]])

        <div class="row">
            <div class="col-lg-12 content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <div id="form-area">
                    <h2>{{ $title }}</h2>
                    <div class="well">
                        {{ Form::model($comment, ['method' => 'PUT', 'route' => ['user.comments.update', 'login' => $user->getLoginForUrl(), 'id' => $comment->id], 'id' => 'comment-form', 'files' => true]) }}

                        <div class="row">
                            <div class="col-md-12">
                                <div class="button-group pull-right">
                                    <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}" class="btn btn-primary btn-sm">
                                        <i class="material-icons">keyboard_arrow_left</i>
                                        <span class="hidden-xxs">Отмена</span>
                                    </a>
                                    {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm']) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('comment', ($comment->is_answer) ? 'Текст ответа' : 'Текст комментария') }}
                                    {{ Form::textarea('comment', $comment->comment, ['class' => 'form-control editor']) }}
                                    {{ $errors->first('comment') }}
                                </div>

                                <!-- TinyMCE image -->
                                {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
                                {{ Form::hidden('tempPath', $comment->getTempPath(), ['id' => 'tempPath']) }}
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
@stop

@section('script')
    @parent

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