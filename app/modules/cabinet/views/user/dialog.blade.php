@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li><a href="{{ URL::route('user.messages', ['login' => $user->login]) }}">Личные сообщения</a></li>
                <li>Сообщения от пользователя {{ $companion->login }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <h3>Люди</h3>
        </div>
        <div class="col-lg-9">
            <h2>Сообщения от пользователя {{ $companion->login }}
                @if($companion->getFullName())
                    ({{ $companion->getFullName() }})
                @endif
            </h2>

            <div id="messages">

                @foreach($messages as $message)

                <div class="row">
                    <div class="col-md-2">
                        @if($user->id == $message->userSender->id)
                            {{ $message->userSender->getAvatar('mini') }}
                            {{ $message->userSender->login }}
                            {{ DateHelper::dateForMessage($message->created_at) }}
                        @endif
                    </div>

                    @if($user->id == $message->userSender->id)
                    <div class="col-md-7">
                        <div class="well">
                            {{ $message->message }}
                        </div>
                    </div>
                    @else
                        <div class="col-md-7 col-md-offset-1">
                            <div class="well {{ is_null($message->read_at) ? 'new-message' : ''}}" data-message-id="{{ $message->id }}">
                                {{ $message->message }}
                            </div>
                        </div>
                    @endif

                    <div class="col-md-2">
                        @if($companion->id == $message->userSender->id)
                            {{ $message->userSender->getAvatar('mini') }}
                            {{ $message->userSender->login }}
                            {{ DateHelper::dateForMessage($message->created_at) }}
                        @endif
                    </div>
                </div>

                @endforeach

            </div>

        </div>
    </div>
@stop

@section('script')
    @parent

    {{-- Отметить сообщение как прочитанное --}}
    <script type="text/javascript">
        $('.new-message').click(function(){
            var messageId = $(this).data('messageId');
            $.ajax({
                url: '<?php echo URL::route('user.markMessageAsRead') ?>',
                dataType: "text json",
                type: "POST",
                data: {messageId: messageId},
                success: function(response) {
                    if(response.success){
                        $('[data-message-id= ' + messageId + ']').removeClass('new-message');
                    }
                }
            });
        });
    </script>
@stop