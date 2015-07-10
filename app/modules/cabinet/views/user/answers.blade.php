@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои ответы' : 'Ответы пользователя ' . $user->login) : 'Ответы пользователя ' . $user->login;
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
                            @elseif(Ip::isBanned())
                                @include('messages.bannedIp')
                            @endif
                        @endif
                    @endif

                    <div id="answers">
                        @if(count($answers))
                            @foreach($answers as $answer)

                                <div data-comment-id="{{ $answer->id }}" class="col-md-12">
                                    <div class="well">
                                        <div class="date date-create">{{ $answer->created_at }}</div>
                                        {{ $answer->comment }}
                                        <div class="status">
                                            Статус:
                                            {{ ($answer->is_published) ? 'Опубликован' : 'Ожидает модерации' }}
                                        </div>
                                        <div class="on-page">
                                            На странице:
                                            @if(($answer->is_published))
                                                <a href="{{ URL::to($answer->getUrl()) }}">{{ $answer->page->getTitle() }}</a>
                                            @else
                                                <a href="{{ URL::to($answer->page->getUrl()) }}">{{ $answer->page->getTitle() }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                            <div>
                                {{ $answers->links() }}
                            </div>
                        @else
                            @if(Auth::user()->is($user))
                                <p>
                                    Вы еще не ответили ни на один вопрос.
                                </p>
                            @else
                                <p>
                                    Ответов нет.
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