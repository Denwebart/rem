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
        </div>
        <div class="col-lg-9">
            <h2>{{ $title }}</h2>

            @if(Auth::check())
                @if(Auth::user()->is($user))
                    @if($user->is_banned)
                        @include('cabinet::user.banMessage')
                    @elseif(Ip::isBanned())
                        @include('messages.bannedIp')
                    @endif
                @endif
            @endif

            <div id="comments">

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
                                @if(($comment->is_published))
                                    <a href="{{ URL::to($comment->getUrl()) }}">{{ $comment->page->getTitle() }}</a>
                                @else
                                    <a href="{{ URL::to($comment->page->getUrl()) }}">{{ $comment->page->getTitle() }}</a>
                                @endif
                            </div>
                        </div>
                    </div>

                @endforeach

                <div>
                    {{ $comments->links() }}
                </div>

            </div>
        </div>
    </div>
@stop