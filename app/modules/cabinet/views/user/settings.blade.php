@extends('cabinet::layouts.cabinet')

<?php
$title = 'Изменение настроек профиля';
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
                <div id="user-settings" class="well">
                    {{ Form::model($userSettings, ['method' => 'POST', 'route' => ['user.postSettings', $user->getLoginForUrl()], 'id' => 'user-settings-form']) }}
                    <div class="row">
                        <div class="col-lg-5 col-md-12 col-sm-5 col-xs-12 pull-right">
                            <div class="button-group">
                                <a href="{{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}}" class="btn btn-primary btn-sm">
                                    <i class="material-icons">keyboard_arrow_left</i>
                                    <span class="hidden-xxs">Отмена</span>
                                </a>
                                {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm']) }}
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-12 col-sm-7 col-xs-12 pull-left">
                            <h3 class="margin-bottom-0">Настройка уведомлений на email</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {{ Form::hidden('notification_deleted', 0, ['id' => 'notification_deleted_uncheck']) }}
                                        {{ Form::checkbox('notification_deleted', 1, $userSettings->notification_deleted) }}
                                        <span class="margin-left-10">Удаление статьи, вопроса, комментария, ответа</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        {{ Form::hidden('notification_points', 0, ['id' => 'notification_points_uncheck']) }}
                                        {{ Form::checkbox('notification_points', 1, $userSettings->notification_points) }}
                                        <span class="margin-left-10">Добавление / вычитание баллов за комментарии, ответы, статьи</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        {{ Form::hidden('notification_new_comments', 0, ['id' => 'notification_new_comments_uncheck']) }}
                                        {{ Form::checkbox('notification_new_comments', 1, $userSettings->notification_new_comments) }}
                                        <span class="margin-left-10">Новые комментарии на статьи и вопросы</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        {{ Form::hidden('notification_new_answers', 0, ['id' => 'notification_new_answers_uncheck']) }}
                                        {{ Form::checkbox('notification_new_answers', 1, $userSettings->notification_new_answers) }}
                                        <span class="margin-left-10">Новые ответы на вопросы</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        {{ Form::hidden('notification_like_dislike', 0, ['id' => 'notification_like_dislike_uncheck']) }}
                                        {{ Form::checkbox('notification_like_dislike', 1, $userSettings->notification_like_dislike) }}
                                        <span class="margin-left-10">Понравился / не понравился ответ или комментарий</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        {{ Form::hidden('notification_best_answer', 0, ['id' => 'notification_best_answer_uncheck']) }}
                                        {{ Form::checkbox('notification_best_answer', 1, $userSettings->notification_best_answer) }}
                                        <span class="margin-left-10">Ответ стал лучшим</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        {{ Form::hidden('notification_rating', 0, ['id' => 'notification_rating_uncheck']) }}
                                        {{ Form::checkbox('notification_rating', 1, $userSettings->notification_rating) }}
                                        <span class="margin-left-10">Новая оценка статьи или вопроса</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        {{ Form::hidden('notification_journal_subscribed', 0, ['id' => 'notification_journal_subscribed_uncheck']) }}
                                        {{ Form::checkbox('notification_journal_subscribed', 1, $userSettings->notification_journal_subscribed) }}
                                        <span class="margin-left-10">Кто-то подписался / отписался на журнал</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        {{ Form::hidden('notification_question_subscribed', 0, ['id' => 'notification_question_subscribed_uncheck']) }}
                                        {{ Form::checkbox('notification_question_subscribed', 1, $userSettings->notification_question_subscribed) }}
                                        <span class="margin-left-10">Кто-то подписался / отписался на вопрос</span>
                                    </label>
                                </div>

                                @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                                    <hr>

                                    <h5>Для всего сайта</h5>

                                    @if(Auth::user()->isAdmin())
                                        <div class="checkbox">
                                            <label>
                                                {{ Form::hidden('notification_all_new_user', 0, ['id' => 'notification_all_new_user_uncheck']) }}
                                                {{ Form::checkbox('notification_all_new_user', 1, $userSettings->notification_all_new_user) }}
                                                <span class="margin-left-10">Новые пользователи</span>
                                            </label>
                                        </div>
                                    @endif

                                    <div class="checkbox">
                                        <label>
                                            {{ Form::hidden('notification_all_new_question', 0, ['id' => 'notification_all_new_question_uncheck']) }}
                                            {{ Form::checkbox('notification_all_new_question', 1, $userSettings->notification_all_new_question) }}
                                            <span class="margin-left-10">Новые вопросы</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            {{ Form::hidden('notification_all_new_article', 0, ['id' => 'notification_all_new_article_uncheck']) }}
                                            {{ Form::checkbox('notification_all_new_article', 1, $userSettings->notification_all_new_article) }}
                                            <span class="margin-left-10">Новые статьи</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            {{ Form::hidden('notification_all_new_answer', 0, ['id' => 'notification_all_new_answer_uncheck']) }}
                                            {{ Form::checkbox('notification_all_new_answer', 1, $userSettings->notification_all_new_answer) }}
                                            <span class="margin-left-10">Новые ответы</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            {{ Form::hidden('notification_all_new_comment', 0, ['id' => 'notification_all_new_comment_uncheck']) }}
                                            {{ Form::checkbox('notification_all_new_comment', 1, $userSettings->notification_all_new_comment) }}
                                            <span class="margin-left-10">Новые комментарии</span>
                                        </label>
                                    </div>
                                @endif
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