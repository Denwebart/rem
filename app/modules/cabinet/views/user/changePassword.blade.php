@extends('cabinet::layouts.cabinet')

<?php
$title = 'Смена пароля';
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
                    Мой профиль
                </a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">
                {{ Form::model($user, ['method' => 'POST', 'route' => ['user.postChangePassword', $user->getLoginForUrl()], 'id' => 'changePassword']) }}
                <div class="row">
                    <div class="col-lg-6">
                        <h2>{{{ $title }}}</h2>
                    </div>
                    <div class="col-lg-6">
                        <div class="button-group">
                            <a href="{{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}}" class="btn btn-primary">
                                <span class="glyphicon glyphicon-arrow-left"></span>
                                Назад
                            </a>
                            {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                </div>

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
@stop