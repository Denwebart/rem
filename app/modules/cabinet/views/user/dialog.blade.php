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
                        <div class="companion{{ ($companion->id == $item->id) ? ' active' : '' }}" data-user-id="{{ $item->id }}">
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
                                <span class="date">
                                    {{ DateHelper::dateForMessage($message->created_at) }}
                                </span>
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
                                <span class="date">
                                    {{ DateHelper::dateForMessage($message->created_at) }}
                                </span>
                            @endif
                        </div>
                    </div>

                @endforeach

            </div>

            <div id="message-form-container well">
                <h3>Отправить сообщение</h3>

                {{ Form::open([
                      'action' => ['CabinetUserController@addMessage', $companion->id],
                      'id' => 'message-form',
                    ])
                }}

                <div class="form-group">
                    {{ Form::textarea('message', '', ['class' => 'form-control', 'placeholder' => 'Сообщение*', 'rows' => 3]); }}
                    <div id="message_error"></div>
                </div>

                {{ Form::submit('Отправить', ['id'=> 'submit', 'class' => 'btn btn-primary']) }}

                {{ Form::close() }}

            </div>
            <!-- end of #message-form -->

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
                            $('#companions small').text(response.newMessages);
                            $('#users-menu .messages small').text(response.newMessages);
                        } else {
                            $('#header-widget .dropdown-messages .dropdown-toggle span').remove();
                            $('#header-widget .dropdown-messages .dropdown-menu').remove();
                            $('#companions small').remove();
                            $('#users-menu .messages small').remove();
                            // как ссылка
                            $('#header-widget .dropdown-messages .dropdown-toggle').remove();
                            $('#header-widget .dropdown-messages').prepend('<a href="<?php echo URL::route('user.messages', ['login' => Auth::user()->login]) ?>"><i class="fa fa-send"></i></a>');
                        }
                    }
                }
            });
        });

        $("#message-form").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    data = $form.serialize(),
                    url = $form.attr('action');
            var posting = $.post(url, { formData: data });
            posting.done(function(response) {
                if(response.fail) {
                    $.each(response.errors, function(index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).addClass('required');
                        $(errorDiv).empty().append(value);
                    });
                    $('#successMessage').empty();
                }
                if(response.success) {
                    var newMessage = '<div data-message-id="' + response.messageId + '" class="row">' +
                            '<div class="col-md-2">' +
                                '<a href="<?php echo URL::route('user.profile', ['login' => $message->userSender->login]) ?>" class="pull-right">' +
                                '<?php echo Auth::user()->getAvatar('mini')?></a>' +
                                '<a href="<?php echo URL::route('user.profile', ['login' => $message->userSender->login]) ?>">' +
                                '<?php echo Auth::user()->login ?>' +
                                '</a>' +
                                '<br><span class="date">' + response.messageCreadedAt + '</span>' +
                            '</div>' +
                            '<div class="col-md-7">' +
                                '<div class="well new-message">' +
                                    response.message
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-2"></div>' +
                        '</div>';

                    $("#messages").append(newMessage);
                    setTimeout(function(){
                        $("[data-message-id^=" + response.messageId + "]").removeClass('new-message');
                    }, 300);
                    $($form).trigger('reset');
                } //success
            }); //done
        });
    </script>

@stop