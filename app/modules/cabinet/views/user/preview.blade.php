@extends('cabinet::layouts.cabinet')

<?php
$title = 'Предпросмотр';
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
            <div class="col-lg-12 content">
                <h2>{{ $title }}</h2>

                <section id="content">

                    <div class="well">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h2>
                                            {{ $page->title }}
                                        </h2>
                                    </div>
                                    <div class="col-md-3">
                                        {{-- Рейтинг --}}
                                        @include('widgets.rating')
                                    </div>
                                </div>

                                <div class="page-info">
                                    <div class="pull-left">
                                        <div class="user pull-left">
                                            <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                                                {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                                                <span class="login pull-left">{{ $page->user->login }}</span>
                                            </a>
                                        </div>
                                        <div class="date pull-left" title="Дата публикации">
                                            <span class="icon mdi-action-today"></span>
                                            <span>{{ DateHelper::dateFormat($page->published_at) }}</span>
                                        </div>
                                    </div>
                                    <div class="pull-right">
                                        <div class="answers-count pull-left" title="Количество ответов">
                                            <span class="icon mdi-communication-forum"></span>
                                            <a href="#answers">
                                                <span class="count-comments">
                                                    {{ count($page->publishedAnswers) }}
                                                </span>
                                            </a>
                                        </div>

                                        <div class="views pull-left" title="Количество просмотров">
                                            <span class="icon mdi-action-visibility"></span>
                                            <span>{{ $page->views }}</span>
                                        </div>

                                    </div>
                                </div>

                                <div class="content">
                                    @if($page->image)
                                        <a class="fancybox" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                                            {{ $page->getImage() }}
                                        </a>
                                    @endif
                                    {{ $page->getContentWithWidget() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <a href="{{ URL::back() }}">Редактировать</a>


                </section>
            </div>
        </div>
    </div>
@stop