@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Личные сообщения' : 'Личные сообщения пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                    </a>
                </li>
                <li>{{ $title }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div id="companions">
                <div class="header">
                    <h3>Собеседники</h3>
                </div>
                <div class="body">
                    @foreach($companions as $item)
                        <div class="companion" data-user-id="{{ $item->id }}">
                            <a href="{{ URL::route('user.dialog', ['login' => $user->getLoginForUrl(), 'companion' => $item->getLoginForUrl()]) }}">
                                {{ $item->getAvatar('mini', ['class' => 'img-responsive']) }}
                                <span>{{ $item->login }}</span>
                                @if($numberOfMessages = count($item->sentMessagesForUser))
                                    <small class="label label-info pull-right">{{ $numberOfMessages }}</small>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <h2>{{ $title }}</h2>

            @if(Auth::check())
                @if(Auth::user()->is($user))
                    @if(!Ip::isBanned())
                        @if($user->is_banned)
                            @include('cabinet::user.banMessage')
                        @endif
                    @else
                        @include('messages.bannedIp')
                    @endif
                @endif
            @endif

            <div id="messages" class="row">

                {{--@foreach($companions as $item)--}}

                    <?php // $message = $item->sentMessages()->orderBy('created_at')->first(); ?>

                    {{--@if(is_object($message))--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-2">--}}

                            {{--</div>--}}

                            {{--<div class="col-md-7 col-md-offset-1">--}}
                                {{--<div class="well {{ is_null($message->read_at) ? 'new-message' : ''}}" data-message-id="{{ $message->id }}">--}}
                                    {{--<a href="{{ URL::route('user.dialog', ['login' => $user->getLoginForUrl(), 'companion' => $message->userSender->getLoginForUrl()]) }}">--}}
                                        {{--{{ $message->message }}--}}
                                    {{--</a>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="col-md-2">--}}
                                {{--<a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}">--}}
                                    {{--{{ $message->userSender->getAvatar('mini') }}--}}
                                {{--</a>--}}
                                {{--<a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}">--}}
                                    {{--{{ $message->userSender->login }}--}}
                                {{--</a>--}}
                                {{--<span class="date">--}}
                                    {{--{{ DateHelper::dateForMessage($message->created_at) }}--}}
                                {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--@endif--}}
                {{--@endforeach--}}
                @if(count($messages))
                    @foreach($messages as $message)
                        <div class="row">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-7 col-md-offset-1">
                                <div class="well {{ is_null($message->read_at) ? 'new-message' : ''}}" data-message-id="{{ $message->id }}">
                                    <a href="{{ URL::route('user.dialog', ['login' => $user->getLoginForUrl(), 'companion' => $message->userSender->getLoginForUrl()]) }}">
                                        {{ $message->message }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}">
                                    {{ $message->userSender->getAvatar('mini') }}
                                </a>
                                <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}">
                                    {{ $message->userSender->login }}
                                </a>
                                    <span class="date">
                                        {{ DateHelper::dateForMessage($message->created_at) }}
                                    </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    @if(Auth::user()->is($user))
                        <p>
                            У вас нет сообщений.
                        </p>
                    @else
                        <p>
                            Сообщений нет.
                        </p>
                    @endif
                @endif
            </div>

        </div>
    </div>
@stop