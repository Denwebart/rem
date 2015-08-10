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

                            <div data-comment-id="{{ $comment->id }}" class="well">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>
                                            @if($comment->page)
                                                <a href="{{ URL::to($comment->getUrl()) }}">
                                                    {{ $comment->page->title }}
                                                </a>
                                            @else
                                                страница удалена
                                            @endif
                                            <div class="pull-right">
                                                @if(Auth::check())
                                                    @if((Auth::user()->is($comment->user) && !$headerWidget->isBannedIp && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                                                        <div class="buttons pull-left">
                                                            <div class="status">
                                                                Статус:
                                                                {{ ($comment->is_published) ? 'Опубликован' : 'Ожидает модерации' }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="date pull-left" title="Дата публикации">
                                            <i class="material-icons">today</i>
                                            {{ DateHelper::dateFormat($comment->created_at) }}
                                        </div>
                                        <div class="pull-right">
                                            <div class="rating pull-left" title="Оценка комментария">
                                                <i class="material-icons">thumbs_up_down</i>
                                                {{ $comment->votes_like - $comment->votes_dislike }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        {{ $comment->comment }}
                                    </div>

                                    <div class="col-md-12">
                                        @if(0 == $comment->parent_id)
                                            <div class="answers">
                                                Ответы на комментарий:
                                                <a href="{{ URL::to($comment->getUrl()) }}">
                                                    {{ count($comment->publishedChildren) }}
                                                </a>
                                            </div>
                                        @else
                                            <div class="parent-comment">
                                                @if(!$comment->parent->is_answer)
                                                    Ответ на комментарий:
                                                @else
                                                    Комментарий к ответу:
                                                @endif
                                                <div class="comment">
                                                    <div class="date pull-left" title="Дата публикации">
                                                        <i class="material-icons">today</i>
                                                        {{ DateHelper::dateFormat($comment->parent->created_at) }}
                                                    </div>
                                                    <div class="user pull-left" title="@if(!$comment->parent->is_answer) Автор комментария @else Автор ответа @endif">
                                                        @if($comment->parent->user)
                                                            <a href="{{ URL::route('user.profile', ['login' => $comment->parent->user->getLoginForUrl() ]) }}" class="author">
                                                                {{ $comment->parent->user->getAvatar('mini', ['width' => '25px']) }}
                                                                {{ $comment->parent->user->login }}
                                                            </a>
                                                        @else
                                                            <a href="javascript:void(0)" class="author">
                                                                {{ (new User)->getAvatar('mini', ['width' => '25px']) }}
                                                                {{ $comment->parent->user_name }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                    @if($comment->page)
                                                        <a href="{{ URL::to($comment->page->getUrl()) }}#comment-{{ $comment->parent->id }}">
                                                            {{ $comment->parent->comment }}
                                                        </a>
                                                    @else
                                                        {{ $comment->parent->comment }}
                                                    @endif
                                                </div>
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