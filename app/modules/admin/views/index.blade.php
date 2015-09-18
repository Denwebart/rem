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
                    <div class="custom-box palette-alizarin">
                        <h3>
                            <a class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newUsers) }}" data-speed="500" data-refresh-interval="10" href="{{ URL::route('admin.users.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}"></a>
                        </h3>
                        <p>Новые пользователи</p>
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            @endif
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="custom-box palette-peter-river">
                    <h3>
                        <a class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newQuestions) }}" data-speed="500" data-refresh-interval="10" href="{{ URL::route('admin.questions.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}"></a>
                    </h3>
                    <p>Новые вопросы</p>
                    <i class="fa fa-question"></i>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="custom-box palette-carrot">
                    <h3>
                        <a class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newArticles) }}" data-speed="500" data-refresh-interval="10" href="{{ URL::route('admin.articles.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}"></a>
                    </h3>
                    <p>Новые статьи</p>
                    <i class="fa fa-file-text"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="custom-box palette-nephritis">
                    <h3>
                        <a class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newComments) + count($headerWidget->newAnswers) }}" data-speed="500" data-refresh-interval="10" href="{{ URL::route('admin.comments.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}"></a>
                    </h3>
                    <p>Новые комментарии и ответы</p>
                    <i class="fa fa-comment"></i>
                </div>
            </div>
        </div>
    </div>
@stop