@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Диалог с пользователем ' . $companion->login : 'Диалог '. $user->login .' с пользователем ' . $companion->login;
View::share('title', $title);
?>
@section('content')
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
        @include('cabinet::user.companions', ['companions' => $companions, 'companionId' => $companion->id])
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li class="home-page">
                <a href="{{ URL::to('/') }}">
                    <i class="material-icons">home</i>
                </a>
            </li>
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
            <li class="hidden-md hidden-xs">{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <h2>
                    {{ $title }}
                    @if($companion->getFullName())
                        ({{ $companion->getFullName() }})
                    @endif
                </h2>

                <div class="hidden-lg hidden-md margin-bottom-10">
                    @include('cabinet::user.companions', ['companions' => $companions, 'companionId' => null])
                </div>

                <div id="messages-area" class="blog">
                    <div class="count">
                        Показано сообщений: <span>{{ $messages->count() }}</span>.
{{--                            Всего: <span>{{ $messages->getTotal() }}</span>.--}}
                    </div>
{{--                        {{ $messages->links() }}--}}
                    <div id="scroll" @if(!count($messages)) class="without-border" @endif>
                        @if(count($messages))
                            @foreach($messages->reverse() as $message)
                                <div class="row item" data-message-id="{{ $message->id }}">
                                    <div class="col-lg-2 col-sm-2 hidden-md hidden-xs">
                                        @if($user->id == $message->userSender->id)
                                            <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}" class="pull-right avatar-link gray-background display-inline-block">
                                                {{ $message->userSender->getAvatar('mini', ['class' => 'avatar circle']) }}
                                                @if($message->userSender->isOnline())
                                                    <span class="is-online-status online" title="Сейчас на сайте"></span>
                                                @else
                                                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($message->userSender->last_activity) }}"></span>
                                                @endif
                                            </a>
                                        @endif
                                    </div>

                                    @if($user->id == $message->userSender->id)
                                        <div class="col-lg-7 col-md-11 col-sm-7 col-xs-11">
                                            <div class="message outgoing">
                                                <div class="login pull-left hidden-lg hidden-sm">
                                                    я
                                                </div>
                                                <span class="date">
                                                    {{ DateHelper::dateForMessage($message->created_at) }}
                                                </span>
                                                <div class="clearfix"></div>
                                                {{ StringHelper::addFancybox($message->message, 'group-message-' . $message->id) }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-7 col-lg-offset-1 col-md-11 col-md-offset-1 col-sm-7 col-sm-offset-1 col-xs-11 col-xs-offset-1">
                                            <div class="message {{ is_null($message->read_at) ? 'new-message' : ''}}" data-message-id="{{ $message->id }}">
                                                <div class="login pull-left hidden-lg hidden-sm">
                                                    {{ $message->userSender->login }}
                                                </div>
                                                <span class="date">
                                                    {{ DateHelper::dateForMessage($message->created_at) }}
                                                </span>
                                                <div class="clearfix"></div>
                                                {{ StringHelper::addFancybox($message->message, 'group-message-' . $message->id) }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-2 col-sm-2 hidden-md hidden-xs">
                                        @if($companion->id == $message->userSender->id)
                                            <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}" class="avatar-link gray-background display-inline-block">
                                                {{ $message->userSender->getAvatar('mini', ['class' => 'avatar circle']) }}
                                                @if($message->userSender->isOnline())
                                                    <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                                                @else
                                                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($message->userSender->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                                                @endif
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="no-messages">Сообщений нет.</p>
                        @endif
                    </div>
                </div>
                {{--{{ $messages->links() }}--}}

                {{--Отправка нового сообщения--}}
                @if(Auth::user()->is($user))

                    @if(!Ip::isBanned())
                        @if(!$user->is_banned)
                            <div id="message-form-container">
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

                                    {{ Form::submit('Отправить', ['id'=> 'submit', 'class' => 'btn btn-primary btn-sm pull-right']) }}

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
    @include('tinymce-init', ['imagePath' => $user->getMessageImagePath(), 'toolbar' => 'bold italic | bullist numlist | link image media emoticons | print preview'])
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

    <!-- Scroll -->
    {{--{{ HTML::script('js/jquery.waypoints.min.js') }}--}}
    {{--{{ HTML::script('js/infinite.min.js') }}--}}
    {{--<script type="text/javascript">--}}
        {{--var infinite = new Waypoint.Infinite({--}}
            {{--element: $('.scroll')[0],--}}
            {{--items: '.scroll',--}}
            {{--more: '.pagination li.active + li a',--}}
            {{--context: $('#messages-area'),--}}
            {{--offset: '50%'--}}
        {{--})--}}
    {{--</script>--}}

    {{--{{ HTML::script('js/jquery.jscroll.min.js') }}--}}
    {{--<script type="text/javascript">--}}
        {{--$(function() {--}}
            {{--$('.scroll').jscroll({--}}
                {{--autoTrigger: true,--}}
                {{--nextSelector: '.pagination li.active + li a',--}}
                {{--contentSelector: 'div.scroll',--}}
                {{--pagingSelector: '.pagination',--}}
                {{--callback: function() {--}}
                    {{--$('ul.pagination:visible:first').hide();--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}
    {{--</script>--}}

    <script type="text/javascript">
        $(document).ready(function(){
            var scrollArea = document.getElementById('scroll');
            scrollArea.scrollTop = scrollArea.scrollHeight;
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
                                $('#header-widget .dropdown-messages').prepend('<a href="<?php echo URL::route('user.messages', ['login' => Auth::user()->getLoginForUrl()]) ?>"><i class="material-icons">send</i></a>');
                            }
                        }
                    }
                });
            });

            $("#message-form").submit(function(event) {
                event.preventDefault ? event.preventDefault() : event.returnValue = false;
                tinyMCE.get("message").save();
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
                            $("#scroll").removeClass('without-border').append(response.newMessageHtml);
                            $('.no-messages').remove();
                            $($form).trigger('reset');

                            var scrollArea = document.getElementById('scroll');
                            scrollArea.scrollTop = scrollArea.scrollHeight;

                            // отметить сообщение как новое
                            setTimeout(function() {
                                $("[data-message-id=" + response.messageId + "]").find('.new-message').css('background', '#ffffff');
                            }, 3000);
                        } //success
                    }
                });
            });
        </script>
    @endif
@stop