@extends('cabinet::layouts.cabinet')

<?php
$title = 'Редактировать статью';
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
                'title' => Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login,
                'url' => URL::route('user.profile', ['login' => $user->getLoginForUrl()])
            ],
            [
                'title' => 'Мой журнал',
                'url' => URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()])
            ],
            [
                'title' => $title
            ]
        ]])

        <div class="row">
            <div class="col-lg-12 content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <div id="form-area">
                    <h2>{{ $title }}</h2>
                    <div class="well">
                        {{ Form::model($article, ['method' => 'PUT', 'route' => ['user.journal.update', 'login' => $user->getLoginForUrl(), 'id' => $article->id], 'id' => 'articleForm', 'files' => true]) }}
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