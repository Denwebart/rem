@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Редактирование информации о фотографии' : 'Редактирование информации о фотографии пользователя ' . $user->login;
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
                'title' => (Auth::user()->is($user)) ? 'Мой автомобиль' : 'Автомобиль пользователя ' . $user->login,
                'url' => URL::route('user.gallery', ['login' => $user->getLoginForUrl()])
            ],
            [
                'title' => $title
            ]
        ]])

        <div class="row">
            <div class="col-md-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <h2>{{ $title }}</h2>
                <div class="well">
                    {{ Form::model($image, ['method' => 'POST', 'route' => ['user.gallery.editPhoto', 'login' => $user->getLoginForUrl(), 'id' => $image->id], 'files' => true, 'id' => 'editPhoto']) }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="button-group margin-bottom-20 pull-right">
                                    <a href="{{ URL::route('user.gallery', ['login' => $user->getLoginForUrl()]) }}" class="btn btn-primary btn-sm">
                                        <i class="material-icons">keyboard_arrow_left</i>
                                        <span class="hidden-xxs">Отмена</span>
                                    </a>
                                    {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm']) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ $image->getImage(null, ['class' => 'margin-bottom-10']) }}
                                        <div class="form-group @if($errors->has('image')) has-error @endif">
                                            {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary btn-sm btn-full file-inputs']) }}
                                            <small class="image_error error text-danger">
                                                {{ $errors->first('image') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group @if($errors->has('title')) has-error @endif">
                                            {{ Form::label('title', 'Заголовок изображения', ['class' => 'control-label']) }}
                                            {{ Form::text('title', $image->title, ['class' => 'form-control']) }}
                                            <small class="title_error error text-danger">
                                                {{ $errors->first('title') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group @if($errors->has('description')) has-error @endif">
                                    {{ Form::label('description', 'Описание изображения', ['class' => 'control-label']) }}
                                    {{ Form::textarea('description', $image->description, ['class' => 'form-control editor']) }}
                                    <small class="description_error error text-danger">
                                        {{ $errors->first('description') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{ Form::hidden('_token', csrf_token()) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}

    <script type="text/javascript">
        tinymce.init({
            plugins: [
                "advlist lists link",
                "wordcount",
                "emoticons"
            ],
            menubar:false,
            relative_urls: true,
            toolbar1: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link emoticons",
            language: 'ru',
            selector: ".editor",
            setup: function (editor) {
                editor.on('init', function() {
                    editor.getDoc().body.style.fontSize = '14px';
                    editor.getDoc().body.style.fontFamily = '"Open Sans", sans-serif';
                    editor.getDoc().body.style.lineHeight = '1.42857';
                });
            }
        });
    </script>
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