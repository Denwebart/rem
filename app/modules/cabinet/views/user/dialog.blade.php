@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Сообщения от пользователя ' . $companion->login : 'Сообщения пользователю '. $user->login .' от пользователя ' . $companion->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3">
        <div id="companions">
            <div class="header">
                <h3>Собеседники</h3>
            </div>
            <div class="body">
                @foreach($companions as $item)
                    <div class="companion{{ ($companion->id == $item->id) ? ' active' : '' }}" data-user-id="{{ $item->id }}">
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
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
            <li>
                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                    {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                </a>
            </li>
            <li>
                <a href="{{ URL::route('user.messages', ['login' => $user->getLoginForUrl()]) }}">
                    {{ (Auth::user()->is($user)) ? 'Личные сообщения' : 'Личные сообщения пользователя ' . $user->login }}
                </a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">
                <h2>
                    {{ $title }}
                    @if($companion->getFullName())
                        ({{ $companion->getFullName() }})
                    @endif
                </h2>

                <div id="messages">
                    @if(isset($messages))
                        @foreach($messages as $message)

                            <div class="row">
                                <div class="col-md-2">
                                    @if($user->id == $message->userSender->id)
                                        <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}" class="pull-right">
                                            {{ $message->userSender->getAvatar('mini') }}
                                        </a>
                                        <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}">
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
                                            {{ StringHelper::addFancybox($message->message, 'group-message-' . $message->id) }}
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-7 col-md-offset-1">
                                        <div class="well {{ is_null($message->read_at) ? 'new-message' : ''}}" data-message-id="{{ $message->id }}">
                                            {{ StringHelper::addFancybox($message->message, 'group-message-' . $message->id) }}
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-2">
                                    @if($companion->id == $message->userSender->id)
                                        <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}">
                                            {{ $message->userSender->getAvatar('mini') }}
                                        </a>
                                        <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}">
                                            {{ $message->userSender->login }}
                                        </a>
                                        <span class="date">
                                        {{ DateHelper::dateForMessage($message->created_at) }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                        @endforeach
                    @endif
                </div>

                {{--Отправка нового сообщения--}}
                @if(Auth::user()->is($user))

                    @if(!Ip::isBanned())
                        @if(!$user->is_banned)
                            <div id="message-form-container well">
                                <h3>Отправить сообщение</h3>

                                {{ Form::open([
                                      'action' => ['CabinetUserController@addMessage', 'login' => $user->getLoginForUrl(), 'companionId' => $companion->id],
                                      'id' => 'message-form',
                                    ])
                                }}

                                    <div class="form-group">
                                        {{ Form::textarea('message', '', ['class' => 'form-control editor', 'id' => 'message', 'placeholder' => 'Сообщение*', 'rows' => 3]); }}
                                        <div id="message_error"></div>
                                    </div>

                                    <!-- TinyMCE image -->
                                    {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}

                                    {{ Form::hidden('_token', csrf_token()) }}

                                    {{ Form::submit('Отправить', ['id'=> 'submit', 'class' => 'btn btn-primary']) }}

                                {{ Form::close() }}

                            </div>
                            <!-- end of #message-form -->
                        @else
                            @include('cabinet::user.banMessage')
                        @endif
                    @else
                        @include('messages.bannedIp')
                    @endif
                @endif
            </div>
        </div>
    </div>
@stop

@section('style')
    @parent

    <!-- FancyBox2 -->
    <link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init', ['page' => $message])
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

    @if(Auth::user()->is($user))
        {{-- Отметить сообщение как прочитанное --}}
        <script type="text/javascript">
            $('.new-message').click(function(){
                var messageId = $(this).data('messageId');
                $.ajax({
                    url: '<?php echo URL::route('user.markMessageAsRead', ['login' => $user->getLoginForUrl()]) ?>',
                    dataType: "text json",
                    type: "POST",
                    data: {messageId: messageId},
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
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
                                $('#header-widget .dropdown-messages').prepend('<a href="<?php echo URL::route('user.messages', ['login' => Auth::user()->getLoginForUrl()]) ?>"><i class="fa fa-send"></i></a>');
                            }
                        }
                    }
                });
            });

            $("#message-form").submit(function(event) {
                event.preventDefault ? event.preventDefault() : event.returnValue = false;
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].setData('');
                }
                var $form = $(this),
                        data = $form.serialize(),
                        url = $form.attr('action');
                $.ajax({
                    url: url,
                    dataType: "text json",
                    type: "POST",
                    data: {formData: data},
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.fail) {
                            $.each(response.errors, function(index, value) {
                                var errorDiv = '#' + index + '_error';
                                $(errorDiv).addClass('required');
                                $(errorDiv).empty().append(value);
                            });
                            $('#successMessage').empty();
                        }
                        if(response.success) {
                            for (instance in CKEDITOR.instances) {
                                CKEDITOR.instances[instance].updateElement();
                            }
                            for (instance in CKEDITOR.instances) {
                                CKEDITOR.instances[instance].setData('');
                            }
                            var newMessage = '<div data-message-id="' + response.messageId + '" class="row">' +
                                    '<div class="col-md-2">' +
                                    '<a href="<?php echo URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) ?>" class="pull-right">' +
                                    '<?php echo Auth::user()->getAvatar('mini')?></a>' +
                                    '<a href="<?php echo URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) ?>">' +
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
                    }
                });
            });
        </script>
    @endif
@stop