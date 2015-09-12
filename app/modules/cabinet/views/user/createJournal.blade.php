@extends('cabinet::layouts.cabinet')

<?php
$title = 'Написать статью';
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
                <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()]) }}">Мой журнал</a>
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
                        {{ Form::model($article, ['method' => 'POST', 'route' => ['user.journal.store', 'login' => $user->getLoginForUrl()], 'id' => 'journalForm', 'files' => true]) }}
                        @include('cabinet::user._journalForm')
                        {{ Form::hidden('_token', csrf_token()) }}
                        {{ Form::close() }}
                    </div>
                </div>
                <div id="preview" style="display: none"></div>
            </div>
        </div>
    </div>
@stop