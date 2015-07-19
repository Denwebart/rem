@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои комментарии' : 'Комментарии пользователя ' . $user->login) : 'Комментарии пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        {{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login }}
                    </a>
                </li>
                <li>{{ $title }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ $user->getAvatar() }}
            </div>

            {{ $areaWidget->leftSidebar() }}

        </div>
        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-12">
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

                    <div id="comments">

                        @if(count($comments))
                            @foreach($comments as $comment)

                                <div data-comment-id="{{ $comment->id }}" class="col-md-12">
                                    <div class="well">
                                        <div class="date date-create">{{ $comment->created_at }}</div>
                                        {{ $comment->comment }}
                                        <div class="status">
                                            Статус:
                                            {{ ($comment->is_published) ? 'Опубликован' : 'Ожидает модерации' }}
                                        </div>
                                        <div class="on-page">
                                            На странице:
                                            @if($comment->page)
                                                <a href="{{ URL::to($comment->getUrl()) }}">{{ $comment->page->getTitle() }}</a>
                                            @else
                                                страница удалена
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                            <div>
                                {{ $comments->links() }}
                            </div>
                        @else
                            @if(Auth::user()->is($user))
                                <p>
                                    Вы еще не создали ни одного комментария.
                                </p>
                            @else
                                <p>
                                    Комментариев нет.
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-lg-12">
                    {{ $areaWidget->contentBottom() }}
                </div>
            </div>
        </div>
    </div>
@stop