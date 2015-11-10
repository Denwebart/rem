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
        @include('widgets.breadcrumbs', ['items' => [
            [
                'title' => Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login,
                'url' => URL::route('user.profile', ['login' => $user->getLoginForUrl()])
            ],
            [
                'title' => (Auth::user()->is($user)) ? 'Личные сообщения' : 'Личные сообщения пользователя ' . $user->login,
                'url' => URL::route('user.messages', ['login' => $user->getLoginForUrl()])
            ],
            [
                'title' => $title
            ]
        ]])

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
                                <a href="javascript:void(0)" class="reload-message pull-right" title="Обновить" data-toggle="tooltip">
                                    <i class="material-icons">autorenew</i>
                                </a>
                                <h3>Отправить сообщение</h3>

                                {{ Form::open([
                                      'action' => ['CabinetUserController@addMessage', 'login' => $user->getLoginForUrl(), 'companionId' => $companion->id],
                                      'id' => 'message-form',
                                    ])
                                }}

                                    <div class="form-group @if($errors->has('message')) has-error @endif">
                                        {{ Form::textarea('message', '', ['class' => 'form-control editor', 'id' => 'message', 'placeholder' => 'Сообщение*', 'rows' => 3]); }}
                                        <small class="message_error error text-danger">
                                            {{ $errors->first('message') }}
                                        </small>
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
                                var companionsMessages = $('#companions').find('[data-user-id="<?php echo $companion->id ?>"]').find('small').text();
                                if(companionsMessages - 1 == 0) {
                                    $('#companions').find('[data-user-id="<?php echo $companion->id ?>"]').find('small').text('').hide();
                                } else {
                                    $('#companions').find('[data-user-id="<?php echo $companion->id ?>"]').find('small').text(companionsMessages - 1).show();
                                }
                                $('#users-menu .messages small').text(response.newMessages).show();
                            } else {
                                $('#header-widget .dropdown-messages .dropdown-toggle span').remove();
                                $('#header-widget .dropdown-messages .dropdown-menu').remove();
                                $('#companions small').text('').hide();
                                $('#users-menu .messages small').text('').hide();
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

                            var scrollArea = document.getElementById('scroll');
                            scrollArea.scrollTop = scrollArea.scrollHeight;

                            $('#header-widget .dropdown-messages .dropdown-toggle span').text(response.allNewMessages);
                            $('#header-widget .dropdown-messages .dropdown-menu .header span').text(response.allNewMessages);
                            $('#header-widget .dropdown-messages .dropdown-menu [data-sender-id="<?php echo $companion->id ?>"]').remove();
                            $('#users-menu .messages small').text(response.allNewMessages).show();

                            $('#companions').find('[data-user-id="<?php echo $companion->id ?>"]')
                                    .find('small').text(response.newMessage).show();
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
                                var errorDiv = '.' + index + '_error';
                                $(errorDiv).parent().addClass('has-error');
                                $(errorDiv).empty().append(value);
                            });
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

                            // отметить предыдущие сообщения как прочитанные
                            $('#messages-area').find('.message').removeClass('new-message');
                            var countNewMessages = $('#header-widget .dropdown-messages .dropdown-toggle span').text();
                            if((countNewMessages - response.countUnreadMessages) <= 0) {
                                $('#header-widget .dropdown-messages .dropdown-toggle span').text(countNewMessages - response.countUnreadMessages);
                                $('#header-widget .dropdown-messages .dropdown-menu .header span').text(countNewMessages - response.countUnreadMessages);
                                $('#header-widget .dropdown-messages .dropdown-menu [data-sender-id="<?php echo $companion->id ?>"]').remove();
                                $('#users-menu .messages small').text(countNewMessages - response.countUnreadMessages).show();
                                $('#companions').find('[data-user-id="<?php echo $companion->id ?>"]').find('small').remove();
                            } else {
                                $('#header-widget .dropdown-messages .dropdown-toggle span').remove();
                                $('#header-widget .dropdown-messages .dropdown-menu').remove();
                                $('#companions small').text('').hide();
                                $('#users-menu .messages small').text('').hide();
                                // как ссылка
                                $('#header-widget .dropdown-messages .dropdown-toggle').remove();
                                $('#header-widget .dropdown-messages').prepend('<a href="<?php echo URL::route('user.messages', ['login' => Auth::user()->getLoginForUrl()]) ?>"><i class="material-icons">send</i></a>');
                            }

                        } //success
                    }
                });
            });
        </script>
    @endif
@stop