@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои комментарии' : 'Комментарии пользователя ' . $user->login) : 'Комментарии пользователя ' . $user->login;
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

                @if(count($comments))

                    <section id="comments-area" class="blog">
                        <div class="count">
                            Показано комментариев: <span>{{ $comments->count() }}</span>.
                            Всего: <span>{{ $comments->getTotal() }}</span>.
                        </div>

                        @foreach($comments as $comment)

                            <div data-comment-id="{{ $comment->id }}" class="well comment">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="date date-created pull-left" title="Дата публикации" data-toggle="tooltip">
                                            <span class="text">Комментарий оставлен</span>
                                            <span class="date">{{ DateHelper::dateFormat($comment->created_at) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        @if(Auth::check())
                                            @if((Auth::user()->is($comment->user) && !IP::isBanned() && !Auth::user()->is_banned && $comment->isEditable()) || Auth::user()->isAdmin())
                                                <div class="buttons pull-right">
                                                    <a href="javascript:void(0)" class="pull-right delete-comment" data-id="{{ $comment->id }}" title="Удалить комментарий" data-toggle="tooltip" data-placement="top">
                                                        <i class="material-icons">delete</i>
                                                    </a>
                                                    <a href="{{ URL::route('user.comments.edit', ['login' => $comment->user->getLoginForUrl(),'id' => $comment->id]) }}" class="pull-right" title="Редактировать комментарий" data-toggle="tooltip">
                                                        <i class="material-icons">mode_edit</i>
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <h3>
                                            @if($comment->page)
                                                <a href="{{ URL::to($comment->getUrl()) }}">
                                                    {{ $comment->page->title }}
                                                </a>
                                            @else
                                                страница удалена
                                            @endif
                                        </h3>
                                        <div class="comment-text">
                                            {{ $comment->comment }}
                                        </div>

                                    </div>
                                    <div class="col-md-2">
                                        <div class="vote" title="Оценка комментария" date-toggle="tooltip">
                                            <div class="vote-dislike">
                                                <i class="material-icons">arrow_drop_up</i>
                                            </div>
                                            <span class="vote-result">
                                                {{ $comment->votes_like - $comment->votes_dislike }}
                                            </span>
                                            <div class="vote-dislike">
                                                <i class="material-icons">arrow_drop_down</i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        @if(0 == $comment->parent_id)
                                            <div class="answers">
                                                Ответы на комментарий:
                                                <a href="{{ URL::to($comment->getUrl()) }}">
                                                    {{ count($comment->publishedChildren) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{ $comments->links() }}
                    </section>
                @else
                    @if(Auth::check())
                        @if(Auth::user()->is($user))
                            <p>
                                Вы еще не создали ни одного комментария.
                            </p>
                        @else
                            <p>
                                Комментариев нет.
                            </p>
                        @endif
                    @else
                        <p>
                            Комментариев нет.
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