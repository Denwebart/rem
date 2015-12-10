@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои вопросы' : 'Вопросы пользователя ' . $user->login) : 'Вопросы пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
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
            <div class="col-lg-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <div class="row">
                    <div class="col-lg-8 col-md-7 col-sm-8 col-xs-12">
                        <h2>{{ $title }}</h2>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-5 col-lg-4">
                        @if(Auth::check())
                            @if(Auth::user()->is($user))
                                @if(!$headerWidget->isBannedIp)
                                    @if(!$user->is_banned)
                                        <div class="button-group-full">
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{ URL::route('admin.questions.create') }}" class="btn btn-success btn-sm btn-full pull-right">
                                                    Задать вопрос
                                                </a>
                                            @else
                                                <a href="{{ URL::route('user.questions.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success btn-sm btn-full pull-right">
                                                    Задать вопрос
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @endif
                    </div>
                    @if(Auth::check())
                        @if(Auth::user()->is($user))
                            @if($headerWidget->isBannedIp)
                                <div class="col-md-12">
                                    @include('messages.bannedIp')
                                </div>
                            @endif
                            @if($user->is_banned)
                                <div class="col-md-12">
                                    @include('cabinet::user.banMessage')
                                </div>
                            @endif
                        @endif
                    @endif
                </div>

                <section id="questions-area" class="blog">
                    <div class="count pull-left">
                        @include('count', ['models' => $questions])
                    </div>
                    <div class="pull-right">
                        {{ Form::open(['method' => 'GET', 'route' => ['user.questions.search', 'login' => Auth::user()->getLoginForUrl()], 'id' => 'filter-form']) }}
                        {{ Form::hidden('without-answer', 0, ['id' => 'without-answer']) }}
                        {{ Form::hidden('without-best-answer', 0, ['id' => 'without-best-answer']) }}
                        <a href="javascript:void(0)" data-attr="without-answer" class="filter-link @if(Request::get('without-answer')) active @endif">
                            <span>Без ответов</span>
                        </a>
                        <a href="javascript:void(0)" data-attr="without-best-answer" class="filter-link margin-left-10 @if(Request::get('without-best-answer')) active @endif">
                            <span>Нерешённые</span>
                        </a>
                        {{ Form::close() }}
                    </div>
                    <div class="clearfix"></div>
                    <div class="list">
                        @include('cabinet::user.questionsList', ['questions' => $questions])
                    </div>
                </section>
            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <!-- Delete Question -->
    @if(Auth::check())
        @if(Auth::user()->is($user) || Auth::user()->isAdmin())
            <script type="text/javascript">
                $('.delete-question').click(function(){
                    var questionId = $(this).data('id');
                    if(confirm('Вы уверены, что хотите удалить вопрос?')) {
                        $.ajax({
                            url: '<?php echo URL::route('user.questions.delete', ['login' => $user->getLoginForUrl()]) ?>',
                            dataType: "text json",
                            type: "POST",
                            data: {questionId: questionId},
                            beforeSend: function(request) {
                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                            },
                            success: function(response) {
                                if(response.success) {
                                    $('#site-messages').prepend(response.message);
                                    setTimeout(function() {
                                        hideSiteMessage($('.site-message'));
                                    }, 2000);

                                    $('[data-question-id=' + questionId + ']').remove();
                                } else {
                                    $('#site-messages').prepend(response.message);
                                    setTimeout(function() {
                                        hideSiteMessage($('.site-message'));
                                    }, 2000);
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endif

    <script type="text/javascript">
        $('.blog').on('click', '.filter-link', function () {
            var $link = $(this);
            if($link.hasClass('active')) {
                $link.removeClass('active');
                $('#' + $link.data('attr')).val(0);
            } else {
                $link.addClass('active');
                $('#' + $link.data('attr')).val(1);
            }
            $("#filter-form").submit();
        });

        $("form[id^='filter-form']").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    data = $form.serialize(),
                    url = $form.attr('action');
            $.ajax({
                url: url,
                type: "get",
                data: {
                    searchData: data,
                    url: '<?php echo Request::url(); ?>'
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    //to change the browser URL to the given link location
                    window.history.pushState({parent: response.url}, '', response.url);

                    if(response.success) {
                        $('.blog .count').html(response.countHtmL);
                        $('.list').html(response.listHtmL);
                    }
                },
            });
        });
    </script>
@stop