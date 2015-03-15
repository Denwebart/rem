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
            <div id="companions">
                <div class="header">
                    <h3>Собеседники</h3>
                </div>
                <div class="body">
                    @foreach($companions as $item)
                        <div class="companion{{ ($companion->id == $item->id) ? ' active' : '' }}">
                            <a href="{{ URL::route('user.dialog', ['login' => $user->login, 'companion' => $item->login]) }}">
                                {{ $item->getAvatar('mini', ['class' => 'img-responsive']) }}
                                <span>{{ $item->login }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
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
                            <a href="{{ URL::route('user.profile', ['login' => $message->userSender->login]) }}" class="pull-right">
                                {{ $message->userSender->getAvatar('mini') }}
                            </a>
                            <a href="{{ URL::route('user.profile', ['login' => $message->userSender->login]) }}">
                                {{ $message->userSender->login }}
                            </a>
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
                            <a href="{{ URL::route('user.profile', ['login' => $message->userSender->login]) }}">
                                {{ $message->userSender->getAvatar('mini') }}
                            </a>
                            <a href="{{ URL::route('user.profile', ['login' => $message->userSender->login]) }}">
                                {{ $message->userSender->login }}
                            </a>
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
                        if(response.newMessages != 0) {
                            $('#header-widget .dropdown-messages .dropdown-toggle span').text(response.newMessages);
                            $('#header-widget .dropdown-messages .dropdown-menu .header span').text(response.newMessages);
                            $('#header-widget .dropdown-messages .dropdown-menu [data-message-id= ' + messageId + ']').remove();
                        } else {
                            $('#header-widget .dropdown-messages .dropdown-toggle span').remove();
                            $('#header-widget .dropdown-messages .dropdown-menu').remove();
                            // как ссылка
                            $('#header-widget .dropdown-messages .dropdown-toggle').remove();
                            $('#header-widget .dropdown-messages').prepend('<a href="<?php echo URL::route('user.messages', ['login' => Auth::user()->login]) ?>"><i class="fa fa-send"></i></a>');
                        }
                    }
                }
            });
        });
    </script>

@stop