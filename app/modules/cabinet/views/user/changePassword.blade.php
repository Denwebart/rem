@extends('cabinet::layouts.cabinet')

<?php
$title = 'Смена пароля';
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
        @include('cabinet::user.userInfo')
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
            <li>
                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                    Мой профиль
                </a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <h2>{{{ $title }}}</h2>
                <div id="change-password" class="well">
                    {{ Form::model($user, ['method' => 'POST', 'route' => ['user.postChangePassword', $user->getLoginForUrl()], 'id' => 'change-password-form']) }}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="button-group without-margin">
                                <a href="{{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}}" class="btn btn-primary btn-sm">
                                    <i class="material-icons">keyboard_arrow_left</i>
                                    Отмена
                                </a>
                                {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm']) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('password', 'Текущий пароль') }}
                                {{ Form::password('password', ['class' => 'form-control']) }}
                                @if ($errors->has('password')) <p class="text-danger">{{ $errors->first('password') }}</p> @endif
                            </div>

                            <div class="form-group">
                                {{ Form::label('newpassword', 'Новый пароль') }}
                                {{ Form::password('newpassword', ['class' => 'form-control']) }}
                                @if ($errors->has('newpassword')) <p class="text-danger">{{ $errors->first('newpassword') }}</p> @endif
                            </div>

                            <div class="form-group">
                                {{ Form::label('newpassword_confirmation', 'Повтор нового пароля') }}
                                {{ Form::password('newpassword_confirmation', ['class' => 'form-control']) }}
                                @if ($errors->has('newpassword_confirmation')) <p class="text-danger">{{ $errors->first('newpassword_confirmation') }}</p> @endif
                            </div>
                            {{ Form::hidden('_token', csrf_token()) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop