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
        @include('widgets.breadcrumbs', ['items' => [
            [
                'title' => 'Мой профиль',
                'url' => URL::route('user.profile', ['login' => $user->getLoginForUrl()])
            ],
            [
                'title' => $title
            ]
        ]])

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
                            <div class="button-group">
                                <a href="{{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}}" class="btn btn-primary btn-sm">
                                    <i class="material-icons">keyboard_arrow_left</i>
                                    <span class="hidden-xxs">Отмена</span>
                                </a>
                                {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm']) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group @if($errors->has('password')) has-error @endif">
                                {{ Form::label('password', 'Текущий пароль', ['class' => 'control-label']) }}
                                {{ Form::password('password', ['class' => 'form-control']) }}
                                <small class="image_error error text-danger">
                                    {{ $errors->first('password') }}
                                </small>
                            </div>

                            <div class="form-group @if($errors->has('newpassword')) has-error @endif">
                                {{ Form::label('newpassword', 'Новый пароль', ['class' => 'control-label']) }}
                                {{ Form::password('newpassword', ['class' => 'form-control']) }}
                                <small class="image_error error text-danger">
                                    {{ $errors->first('newpassword') }}
                                </small>
                            </div>

                            <div class="form-group @if($errors->has('newpassword')) has-error @endif">
                                {{ Form::label('newpassword_confirmation', 'Повтор нового пароля', ['class' => 'control-label']) }}
                                {{ Form::password('newpassword_confirmation', ['class' => 'form-control']) }}
                                <small class="image_error error text-danger">
                                    {{ $errors->first('newpassword_confirmation') }}
                                </small>
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