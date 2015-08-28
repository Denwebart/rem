@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Личные сообщения' : 'Личные сообщения пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3">
        @include('cabinet::user.companions', ['companions' => $companions, 'companionId' => null])
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
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12 col-md-12" id="content">
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

                <div id="messages-area" class="blog">
                    <div class="count">
                        Недавние диалоги: <span>{{ $messages->count() }}</span>.
                    </div>
                    <div id="scroll" @if(!count($messages)) class="without-border" @endif>
                        @if(count($messages))
                            @foreach($messages as $message)
                                <div class="row item" data-message-id="{{ $message->id }}">
                                    <div class="col-md-9 col-md-offset-1">
                                        <a href="{{ URL::route('user.dialog', ['login' => $user->getLoginForUrl(), 'companion' => $message->userSender->getLoginForUrl()]) }}" class="message link {{ is_null($message->read_at) ? 'new-message' : ''}}" data-message-id="{{ $message->id }}">
                                                <span class="date">
                                                    {{ DateHelper::dateForMessage($message->created_at) }}
                                                </span>
                                            <div class="clearfix"></div>
                                            {{ StringHelper::addFancybox($message->message, 'group-message-' . $message->id) }}
                                        </a>
                                    </div>

                                    <div class="col-md-2">
                                        <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}" class="avatar-link gray-background display-inline-block">
                                            {{ $message->userSender->getAvatar('mini', ['class' => 'avatar circle']) }}
                                            @if($message->userSender->isOnline())
                                                <span class="is-online-status online" title="Сейчас на сайте"></span>
                                            @else
                                                <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($message->userSender->last_activity) }}"></span>
                                            @endif
                                        </a>
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
        </div>
    </div>
@stop

@section('style')
    @parent

    <!-- FancyBox2 -->
    <link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
@endsection

@section('script')
    @parent

    <!-- FancyBox2 -->
    {{HTML::script('fancybox/jquery.fancybox.pack.js?v=2.1.5')}}
    <script type="text/javascript">
        $(document).ready(function() {
            $(".fancybox").fancybox();
        });
    </script>
@endsection