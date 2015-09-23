@extends('admin::layouts.admin')

<?php
$title = 'Административная панель сайта';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-home"></i>
            {{ $title }}
            <small></small>
        </h1>
    </div>
    <div class="content">
        <div class="row">
            @if(Auth::user()->isAdmin())
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <a class="custom-box palette-alizarin" href="{{ URL::route('admin.users.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}">
                        <h3>
                            <span class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newUsers) }}" data-speed="500" data-refresh-interval="10"></span>
                        </h3>
                        <span>Новые пользователи</span>
                        <i class="fa fa-users"></i>
                    </a>
                </div>
            @endif
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a class="custom-box palette-peter-river" href="{{ URL::route('admin.questions.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}">
                    <h3>
                        <span class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newQuestions) }}" data-speed="500" data-refresh-interval="10"></span>
                    </h3>
                    <span>Новые вопросы</span>
                    <i class="fa fa-question"></i>
                </a>
            </div><!-- ./col -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a class="custom-box palette-carrot" href="{{ URL::route('admin.articles.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}">
                    <h3>
                        <span class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newArticles) }}" data-speed="500" data-refresh-interval="10"></span>
                    </h3>
                    <span>Новые статьи</span>
                    <i class="fa fa-file-text"></i>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a class="custom-box palette-nephritis" href="{{ URL::route('admin.comments.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}">
                    <h3>
                        <span class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newComments) + count($headerWidget->newAnswers) }}" data-speed="500" data-refresh-interval="10"></span>
                    </h3>
                    <span>Новые комментарии и ответы</span>
                    <i class="fa fa-comment"></i>
                </a>
            </div>
        </div>
    </div>
@stop