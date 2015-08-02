@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Редактирование информации о фотографии' : 'Редактирование информации о фотографии пользователя ' . $user->login;
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
            <li>
                <a href="{{ URL::route('user.gallery', ['login' => $user->getLoginForUrl()]) }}">
                    {{ (Auth::user()->is($user)) ? 'Мой автомобиль' : 'Автомобиль пользователя ' . $user->login }}
                </a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-md-12" id="content">
                <h2>{{ $title }}</h2>

                {{ Form::model($image, ['method' => 'POST', 'route' => ['user.gallery.editPhoto', 'login' => $user->getLoginForUrl(), 'id' => $image->id], 'files' => true, 'id' => 'editPhoto']) }}

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                                        {{ $errors->first('image') }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    {{ $image->getImage() }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('title', 'Заголовок изображения') }}
                                {{ Form::text('title', $image->title, ['class' => 'form-control']) }}
                                {{ $errors->first('title') }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('description', 'Описание изображения') }}
                                {{ Form::textarea('description', $image->description, ['class' => 'form-control editor']) }}
                                {{ $errors->first('description') }}
                            </div>
                            <div class="button-group">
                                {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
                            </div>
                        </div>
                    </div>

                    <!-- TinyMCE image -->
                    {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}

                    {{ Form::hidden('_token', csrf_token()) }}

                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init', ['imagePath' => $image->getImageEditorPath(), 'toolbar' => 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link emoticons'])
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