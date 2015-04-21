@extends('cabinet::layouts.cabinet')

<?php
$title = 'Написать статью';
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
                    <a href="{{ URL::route('user.journal', ['login' => $user->getLoginForUrl()]) }}">Мой журнал</a>
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
                {{ Form::model($article, ['method' => 'POST', 'route' => ['user.journal.store', 'login' => $user->getLoginForUrl()]], ['id' => 'journalForm']) }}
                @include('cabinet::user._journalForm')
                {{ Form::close() }}
            </div>

        </div>
    </div>
@stop