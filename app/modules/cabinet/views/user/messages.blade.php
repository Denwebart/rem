@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li>Личные сообщения</li>
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
                            <a href="{{ URL::route('user.dialog', ['login' => $user->login, 'companion' => $item->login]) }}">
                                {{ $item->getAvatar('mini', ['class' => 'img-responsive']) }}
                                <span>{{ $item->login }}</span>
                                <?php $numberOfMessages = count($item->sentMessages()->whereNull('read_at')->where('user_id_recipient', '=', $user->id)->get()); ?>
                                @if($numberOfMessages)
                                    <small class="label label-info pull-right">{{ $numberOfMessages }}</small>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <h2>Личные сообщения</h2>

            <div id="messages" class="row">

                @foreach($companions as $item)

                    <?php $message = $item->sentMessages()->orderBy('created_at')->first(); ?>

                    @if(is_object($message))
                        <div class="row">
                            <div class="col-md-2">

                            </div>

                            <div class="col-md-7 col-md-offset-1">
                                <div class="well {{ is_null($message->read_at) ? 'new-message' : ''}}" data-message-id="{{ $message->id }}">
                                    {{ $message->message }}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <a href="{{ URL::route('user.profile', ['login' => $message->userSender->login]) }}">
                                    {{ $message->userSender->getAvatar('mini') }}
                                </a>
                                <a href="{{ URL::route('user.profile', ['login' => $message->userSender->login]) }}">
                                    {{ $message->userSender->login }}
                                </a>
                                <span class="date">
                                    {{ DateHelper::dateForMessage($message->created_at) }}
                                </span>
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>

        </div>
    </div>
@stop