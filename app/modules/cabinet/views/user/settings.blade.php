@extends('cabinet::layouts.cabinet')

<?php
$title = 'Изменение настроек профиля';
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
                <h2>{{{ $title }}}</h2>
                <div id="user-settings" class="well">
                    {{ Form::model($userSettings, ['method' => 'POST', 'route' => ['user.postSettings', $user->getLoginForUrl()], 'id' => 'user-settings-form']) }}
                    <div class="row">
                        <div class="col-md-7">
                            <h3>Настройка уведомлений на email</h3>
                        </div>
                        <div class="col-lg-5">
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
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="togglebutton">
                                    <label>
                                        {{ Form::hidden('notification_deleted', 0, ['id' => 'notification_deleted_uncheck']) }}
                                        {{ Form::checkbox('notification_deleted', 1, $userSettings->notification_deleted) }}
                                        Удаление статьи, вопроса, комментария, ответа
                                    </label>
                                </div>
                                <div class="togglebutton">
                                    <label>
                                        {{ Form::hidden('notification_points', 0, ['id' => 'notification_points_uncheck']) }}
                                        {{ Form::checkbox('notification_points', 1, $userSettings->notification_points) }}
                                        Добавление / вычитание баллов за комментарии, ответы, статьи
                                    </label>
                                </div>
                                <div class="togglebutton">
                                    <label>
                                        {{ Form::hidden('notification_new_comments', 0, ['id' => 'notification_new_comments_uncheck']) }}
                                        {{ Form::checkbox('notification_new_comments', 1, $userSettings->notification_new_comments) }}
                                        Новые комментарии на статьи и вопросы
                                    </label>
                                </div>
                                <div class="togglebutton">
                                    <label>
                                        {{ Form::hidden('notification_new_answers', 0, ['id' => 'notification_new_answers_uncheck']) }}
                                        {{ Form::checkbox('notification_new_answers', 1, $userSettings->notification_new_answers) }}
                                        Новые ответы на вопросы
                                    </label>
                                </div>
                                <div class="togglebutton">
                                    <label>
                                        {{ Form::hidden('notification_like_dislike', 0, ['id' => 'notification_like_dislike_uncheck']) }}
                                        {{ Form::checkbox('notification_like_dislike', 1, $userSettings->notification_like_dislike) }}
                                        Понравился / не понравился ответ или комментарий
                                    </label>
                                </div>
                                <div class="togglebutton">
                                    <label>
                                        {{ Form::hidden('notification_best_answer', 0, ['id' => 'notification_best_answer_uncheck']) }}
                                        {{ Form::checkbox('notification_best_answer', 1, $userSettings->notification_best_answer) }}
                                        Ответ стал лучшим
                                    </label>
                                </div>
                                <div class="togglebutton">
                                    <label>
                                        {{ Form::hidden('notification_rating', 0, ['id' => 'notification_rating_uncheck']) }}
                                        {{ Form::checkbox('notification_rating', 1, $userSettings->notification_rating) }}
                                        Новая оценка статьи или вопроса
                                    </label>
                                </div>
                                <div class="togglebutton">
                                    <label>
                                        {{ Form::hidden('notification_journal_subscribed', 0, ['id' => 'notification_journal_subscribed_uncheck']) }}
                                        {{ Form::checkbox('notification_journal_subscribed', 1, $userSettings->notification_journal_subscribed) }}
                                        Кто-то подписался / отписался на журнал
                                    </label>
                                </div>
                                <div class="togglebutton">
                                    <label>
                                        {{ Form::hidden('notification_question_subscribed', 0, ['id' => 'notification_question_subscribed_uncheck']) }}
                                        {{ Form::checkbox('notification_question_subscribed', 1, $userSettings->notification_question_subscribed) }}
                                        Кто-то подписался / отписался на вопрос
                                    </label>
                                </div>
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