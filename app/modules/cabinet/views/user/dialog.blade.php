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
                    <div class="count pull-left">
                        Показано сообщений: <span>{{ $messages->count() }}</span>.
{{--                            Всего: <span>{{ $messages->getTotal() }}</span>.--}}
                    </div>
                    <a href="javascript:void(0)" class="reload-message pull-right" title="Обновить" data-toggle="tooltip">
                        <i class="material-icons">autorenew</i>
                    </a>
{{--                        {{ $messages->links() }}--}}
                    <div class="clearfix"></div>
                    <div id="scroll" @if(!count($messages)) class="without-border" @endif>
                        @include('cabinet::user.messagesList')
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
                                    {{ Form::hidden('tempPath', '/uploads/temp/' . Str::random(20) . '/', ['id' => 'tempPath']) }}

                                    {{ Form::hidden('_token', csrf_token()) }}

                                    <div class="row">
                                        <div class="col-md-4 col-md-offset-8 col-sm-4 col-sm-offset-8 col-xs-12 col-xs-offset-0">
                                            {{ Form::submit('Отправить', ['id'=> 'submit', 'class' => 'btn btn-primary btn-full btn-sm pull-right']) }}
                                        </div>
                                    </div>

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
    @include('tinymce-init', ['toolbar' => 'bold italic | bullist numlist | link image media emoticons | print preview'])
@stop

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

            $('.reload-message').click(function(){
                $.ajax({
                    url: '<?php echo URL::route('user.reloadMessages', ['login' => $user->getLoginForUrl(), 'comanion' =>$companion->getLoginForUrl()]) ?>',
                    dataType: "text json",
                    type: "POST",
                    data: {},
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success){
                            $('#scroll').html(response.messagesListHtml);
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