@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои ответы' : 'Ответы пользователя ' . $user->login) : 'Ответы пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
            <li>
                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                    {{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login }}
                </a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">
                <h2>{{ $title }}</h2>

                @if(Auth::check())
                    @if(Auth::user()->is($user))
                        @if($user->is_banned)
                            @include('cabinet::user.banMessage')
                        @elseif($headerWidget->isBannedIp)
                            @include('messages.bannedIp')
                        @endif
                    @endif
                @endif

                @if(count($answers))
                    <section id="answers-area" class="blog">
                        <div class="count">
                            Показано ответов: <span>{{ $answers->count() }}</span>.
                            Всего: <span>{{ $answers->getTotal() }}</span>.
                        </div>

                        @foreach($answers as $answer)
                            <div data-comment-id="{{ $answer->id }}" class="well comment">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="date date-created pull-left" title="Дата публикации" data-toggle="tooltip">
                                            <span class="text">Ответ оставлен</span>
                                            <span class="date">{{ DateHelper::dateFormat($answer->created_at) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        @if(Auth::check())
                                            @if((Auth::user()->is($answer->user) && !IP::isBanned() && !Auth::user()->is_banned && $answer->isEditable()) || Auth::user()->isAdmin())
                                                <div class="buttons pull-right">
                                                    <a href="javascript:void(0)" class="pull-right delete-comment" data-id="{{ $answer->id }}" title="Удалить комментарий" data-toggle="tooltip" data-placement="top">
                                                        <i class="material-icons">delete</i>
                                                    </a>
                                                    <a href="{{ URL::route('user.comments.edit', ['login' => $answer->user->getLoginForUrl(),'id' => $answer->id]) }}" class="pull-right" title="Редактировать комментарий" data-toggle="tooltip">
                                                        <i class="material-icons">mode_edit</i>
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <h3>
                                            @if($answer->page)
                                                <a href="{{ URL::to($answer->getUrl()) }}">
                                                    {{ $answer->page->title }}
                                                </a>
                                            @else
                                                страница удалена
                                            @endif
                                        </h3>
                                        <div class="comment-text @if(Comment::MARK_BEST == $answer->mark) best @endif">
                                            <div class="row">
                                                @if(Comment::MARK_BEST != $answer->mark)
                                                    <div class="col-md-12">
                                                        {{ $answer->comment }}
                                                    </div>
                                                @else
                                                    <div class="col-md-11">
                                                        {{ $answer->comment }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="best pull-left" title="Ответ стал лучшим" data-toggle="tooltip">
                                                            <i class="material-icons mdi-success">done</i>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="vote" title="Оценка комментария" date-toggle="tooltip">
                                            <div class="vote-dislike">
                                                <i class="material-icons">arrow_drop_up</i>
                                            </div>
                                            <span class="vote-result">
                                                {{ $answer->votes_like - $answer->votes_dislike }}
                                            </span>
                                            <div class="vote-dislike">
                                                <i class="material-icons">arrow_drop_down</i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        @if(0 == $answer->parent_id)
                                            <div class="answers">
                                                Комментарии к ответу:
                                                <a href="{{ URL::to($answer->getUrl()) }}">
                                                    {{ count($answer->publishedChildren) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{ $answers->links() }}
                    </section>
                @else
                    @if(Auth::check())
                        @if(Auth::user()->is($user))
                            <p>
                                Вы еще не ответили ни на один вопрос.
                            </p>
                        @else
                            <p>
                                Ответов нет.
                            </p>
                        @endif
                    @else
                        <p>
                            Ответов нет.
                        </p>
                    @endif
                @endif
            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
@stop