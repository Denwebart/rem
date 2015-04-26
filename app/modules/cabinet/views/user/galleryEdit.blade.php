@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Редактирование информации о фотографии' : 'Редактирование информации о фотографии пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
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
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ $user->getAvatar() }}
            </div>
        </div>
        <div class="col-lg-9">
            <h2>{{ (Auth::user()->is($user)) ? 'Мой автомобиль' : 'Автомобиль пользователя ' . $user->login }}</h2>

            Загрузка новой фотографии
            <div id="new-photo">

                <h3>{{ $title }}</h3>

                {{ Form::model($image, ['method' => 'POST', 'route' => ['user.gallery.editPhoto', 'login' => $user->getLoginForUrl(), 'id' => $image->id], 'files' => true], ['id' => 'editPhoto']) }}

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            {{ Html::image($image->getImageUrl($user), $image->desctiption, ['class' => 'img-responsive']) }}
                            {{ Form::file('image', ['title' => 'Загрузить изображения', 'class' => 'btn btn-primary file-inputs']) }}
                            {{ $errors->first('avatar') }}
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group">
                            {{ Form::label('title', 'Заголовок изображения') }}
                            {{ Form::text('title', $image->title, ['class' => 'form-control']) }}
                            {{ $errors->first('title') }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('description', 'Описание изображения') }}
                            {{ Form::textarea('description', $image->description, ['class' => 'form-control']) }}
                            {{ $errors->first('description') }}
                        </div>

                        <div class="button-group">
                            {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                </div>

                {{ Form::close() }}

            </div>

        </div>
    </div>
@stop

@section('script')
    @parent

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replace('description', {
            toolbar: [
                {name: 'paragraph', items: ['NumberedList', 'BulletedList']},
                {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike']},
                {name: 'links', items: ['Link', 'Unlink']},
            ]
        })
    </script>

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();
    </script>

@stop