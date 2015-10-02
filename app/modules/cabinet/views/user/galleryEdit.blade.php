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
        <ol class="breadcrumb">
            <li class="home-page">
                <a href="{{ URL::to('/') }}">
                    <i class="material-icons">home</i>
                </a>
            </li>
            <li>
                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                    {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                </a>
            </li>
            <li>
                <a href="{{ URL::route('user.gallery', ['login' => $user->getLoginForUrl()]) }}">
                    {{ (Auth::user()->is($user)) ? 'Мой автомобиль' : 'Автомобиль пользователя ' . $user->login }}
                </a>
            </li>
            <li class="hidden-md hidden-xs">{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-md-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <h2>{{ $title }}</h2>
                <div class="well">
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

                    {{ Form::model($image, ['method' => 'POST', 'route' => ['user.gallery.editPhoto', 'login' => $user->getLoginForUrl(), 'id' => $image->id], 'files' => true, 'id' => 'editPhoto']) }}

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ $image->getImage(null, ['class' => 'margin-bottom-10']) }}
                                        <div class="form-group">
                                            {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary btn-sm btn-full file-inputs']) }}
                                            {{ $errors->first('image') }}
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            {{ Form::label('title', 'Заголовок изображения') }}
                                            {{ Form::text('title', $image->title, ['class' => 'form-control']) }}
                                            {{ $errors->first('title') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('description', 'Описание изображения') }}
                                    {{ Form::textarea('description', $image->description, ['class' => 'form-control editor']) }}
                                    {{ $errors->first('description') }}
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