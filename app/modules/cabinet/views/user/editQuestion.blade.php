@extends('cabinet::layouts.cabinet')

<?php
$title = 'Редактировать вопрос';
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
                <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}">Мои вопросы</a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">
                <h2>{{ $title }}</h2>

                <div class="row">
                    {{ Form::model($question, ['method' => 'PUT', 'route' => ['user.questions.update', 'login' => $user->getLoginForUrl(), 'id' => $question->id], 'id' => 'questionForm', 'files' => true]) }}
                    @include('cabinet::user._questionsForm')
                    {{ Form::hidden('_token', csrf_token()) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop