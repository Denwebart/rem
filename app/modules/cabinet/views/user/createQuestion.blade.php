@extends('cabinet::layouts.cabinet')

<?php
$title = 'Задать вопрос';
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
                <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}">Мои вопросы</a>
            </li>
            <li class="hidden-md hidden-xs">{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12 content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <div id="form-area">
                    <h2>{{ $title }}</h2>
                    <div class="well">
                        <div class="row">
                            {{ Form::model($question, ['method' => 'POST', 'route' => ['user.questions.store', 'login' => $user->getLoginForUrl()], 'id' => 'questionForm', 'files' => true]) }}
                            @include('cabinet::user._questionsForm')
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