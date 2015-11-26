@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Личные сообщения' : 'Личные сообщения пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
        @include('cabinet::user.companions', ['companions' => $companions, 'companionId' => null])
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        @include('widgets.breadcrumbs', ['items' => [
            [
                'title' => Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login,
                'url' => URL::route('user.profile', ['login' => $user->getLoginForUrl()])
            ],
            [
                'title' => $title
            ]
        ]])

        <div class="row">
            <div class="col-lg-12 col-md-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

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

                <div class="hidden-lg hidden-md margin-bottom-10">
                    @include('cabinet::user.companions', ['companions' => $companions, 'companionId' => null])
                </div>

                <div id="messages-area" class="blog">
                    <div class="count pull-left">
                        Недавние диалоги: <span>{{ $messages->count() }}</span>.
                    </div>
                    <a href="javascript:void(0)" class="delete-all-dialogs pull-right margin-left-10" title="Удалить все диалоги" data-toggle="tooltip">
                        <i class="material-icons">delete</i>
                    </a>
                    <div class="clearfix"></div>
                    <div id="scroll" @if(!count($messages)) class="without-border" @endif>
                        @if(count($messages))
                            @foreach($messages as $message)
                                <div class="row item" data-message-id="{{ $message->id }}">
                                    <div class="col-lg-9 col-lg-offset-1 col-md-10 col-md-offset-0 col-sm-9 col-sm-offset-1 col-xs-10 col-xs-offset-0">
                                        <a href="{{ URL::route('user.dialog', ['login' => $user->getLoginForUrl(), 'companion' => $message->userSender->getLoginForUrl()]) }}" class="message link {{ is_null($message->read_at) ? 'new-message' : ''}}" data-message-id="{{ $message->id }}">
                                            <span class="date">
                                                {{ DateHelper::dateForMessage($message->created_at) }}
                                            </span>
                                            <div class="clearfix"></div>
                                            {{ StringHelper::addFancybox(StringHelper::limit($message->message, 100), 'group-message-' . $message->id) }}
                                        </a>
                                    </div>

                                    <div class="col-md-2 col-xs-2">
                                        <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}" class="avatar-link gray-background display-inline-block">
                                            {{ $message->userSender->getAvatar('mini', ['class' => 'avatar circle', 'data-placement' => 'left']) }}
                                            @if($message->userSender->isOnline())
                                                <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="left"></span>
                                            @else
                                                <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($message->userSender->last_activity) }}" data-toggle="tooltip" data-placement="left"></span>
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

@section('script')
    @parent

    <!-- FancyBox2 -->
    {{-- стили в fonts.css --}}
    {{--<link rel="stylesheet" href="/fancybox/jquery.fancybox.min.css?v=2.1.5" type="text/css" media="screen" />--}}
    {{HTML::script('fancybox/jquery.fancybox.pack.min.js?v=2.1.5')}}
    <script type="text/javascript">
        $(document).ready(function() {
            $(".fancybox").fancybox();
        });
    </script>

    @if(Auth::user()->is($user))
        <script type="text/javascript">
            // Удаление диалогов
            $('.delete-all-dialogs').on('click', function(){
                var $button = $(this);
                if(confirm('Вы уверены, что хотите удалить все диалоги?')) {
                    $.ajax({
                        url: "{{ URL::route('user.deleteAllMessages', ['login' => Auth::user()->getLoginForUrl()])}}",
                        dataType: "text json",
                        type: "POST",
                        data: {userId: '<?php echo Auth::user()->id ?>'},
                        beforeSend: function (request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                        },
                        success: function (response) {
                            if (response.success) {
                                $button.parent().find('.tooltip').remove();
                                $('#site-messages').prepend(response.message);
                                $('#scroll').html('<p class="no-messages">Сообщений нет.</p>');

                                $('#header-widget .dropdown-messages .dropdown-toggle span').remove();
                                $('#header-widget .dropdown-messages .dropdown-menu').remove();
                                $('#companions small').text('').hide();
                                $('#users-menu .messages small').text('').hide();
                                // как ссылка
                                $('#header-widget .dropdown-messages .dropdown-toggle').remove();
                                $('#header-widget .dropdown-messages').prepend('<a href="<?php echo URL::route('user.messages', ['login' => Auth::user()->getLoginForUrl()]) ?>"><i class="material-icons">send</i></a>');
                            } else {
                                $('#site-messages').prepend(response.message);
                            }
                        }
                    });
                }
            });
        </script>
    @endif
@stop