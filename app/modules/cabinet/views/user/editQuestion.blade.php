@extends('cabinet::layouts.cabinet')

<?php
$title = 'Задать вопрос';
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
                    <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}">Мои вопросы</a>
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
            <h2>{{ $title }}</h2>

            <div class="row">
                {{ Form::model($question, ['method' => 'PUT', 'route' => ['user.questions.update', 'login' => $user->getLoginForUrl(), 'id' => $question->id]], ['id' => 'questionForm']) }}
                @include('cabinet::user._questionsForm')
                {{ Form::close() }}
            </div>

        </div>
    </div>
@stop