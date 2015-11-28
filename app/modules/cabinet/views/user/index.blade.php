@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login;
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
                'title' => $title
            ]
        ]])

        <div class="row">
            <div class="col-md-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <div id="profile" class="well">
                    <div class="row">
                        <div class="col-md-12">

                            <!-- всплывающее сообщение - согласие с правилами сайта -->
                            @if(Session::has('rulesSuccessMessage'))
                                @section('siteMessages')
                                    @include('widgets.siteMessages.success', ['siteMessage' => Session::get('rulesSuccessMessage')])
                                    @parent
                                @stop
                            @endif

                            <!-- всплывающее сообщение - пароль изменен -->
                            @if(Session::has('successMessage'))
                                @section('siteMessages')
                                    @include('widgets.siteMessages.success', ['siteMessage' => Session::get('successMessage')])
                                    @parent
                                @stop
                            @endif

                            @if(Auth::check())
                                @if(Auth::user()->is($user) && !Auth::user()->is_agree)
                                    @include('messages.rulesAgree')
                                @endif

                                @if(Auth::user()->is($user) && Auth::user()->is_banned)
                                    @include('cabinet::user.banMessage')
                                @endif

                                @if(Auth::user()->is($user) && $headerWidget->isBannedIp)
                                    @include('messages.bannedIp')
                                @endif
                            @endif

                            <div class="row">
                                <div class="col-md-3 col-sm-3 pull-right">
                                    @if(Auth::check())
                                        <div class="buttons pull-right">
                                            @if((Auth::user()->is($user) && !$headerWidget->isBannedIp && !$user->is_banned) || Auth::user()->isAdmin())
                                                <a href="{{{ URL::route('user.edit', ['login' => $user->getLoginForUrl()]) }}}" class="pull-left" title="Редактировать профиль" data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                            @endif
                                            @if(Auth::user()->is($user))
                                                <a href="{{{ URL::route('user.changePassword', ['login' => $user->getLoginForUrl()]) }}}" class="pull-left" title="Поменять пароль" data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">security</i>
                                                </a>
                                            @endif
                                            @if(Auth::user()->is($user))
                                                <a href="{{{ URL::route('user.settings', ['login' => $user->getLoginForUrl()]) }}}" class="pull-left" title="Настройки" data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">settings</i>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9 col-sm-9 pull-left">
                                    <h2>
                                        <span class="login">
                                            {{{ $user->login }}}
                                        </span>
                                        @if($user->getFullName())
                                            |
                                            <span class="fullname">{{{ $user->getFullName() }}}</span>
                                        @endif
                                    </h2>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8 col-md-9 col-sm-8">
                                    <div class="row">
                                        @if($user->isAdmin() || $user->isModerator())
                                            <div class="col-sm-6 col-md-6 col-lg-6">
                                                <div class="user-data-row role">
                                                    <span title="Права" data-toggle="tooltip" data-placement="right">
                                                        <i class="material-icons">perm_identity</i>
                                                        <span>
                                                            {{ User::$roles[$user->role] }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        @if(Auth::check())
                                            @if(Auth::user()->is($user) || Auth::user()->isAdmin() || Auth::user()->isModerator())
                                                <div class="col-sm-6 col-md-6 col-lg-6">
                                                    <div class="user-data-row email">
                                                        <span title="Email виден только вам" data-toggle="tooltip" data-placement="right">
                                                            <i class="material-icons">email</i>
                                                            <span>
                                                                {{{ $user->email }}}
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <div class="user-data-row date date-register">
                                                <span title="Дата регистрации" data-toggle="tooltip" data-placement="right">
                                                    <i class="material-icons pull-left">today</i>
                                                    <span>
                                                        {{{ DateHelper::dateFormat($user->created_at) }}}
                                                    </span>
                                                </span>
                                            </div>

                                            @if($user->country || $user->city)
                                                <div class="user-data-row location">
                                                    <span title="Местоположение" data-toggle="tooltip" data-placement="right">
                                                        <i class="material-icons">place</i>
                                                        <span>
                                                            @if($user->country)
                                                                <span class="country">
                                                                    {{{ $user->country }}}@if($user->city),&nbsp;@endif
                                                                </span>
                                                            @endif
                                                            @if($user->city)
                                                                <span class="city">
                                                                    {{{ $user->city }}}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            @if($user->car_brand)
                                                <div class="user-data-row car-brand">
                                                    <span title="Марка / модель автомобиля" data-toggle="tooltip" data-placement="right">
                                                        <i class="material-icons">directions_car</i>
                                                        <span>
                                                            {{{ $user->car_brand }}}
                                                        </span>
                                                    </span>
                                                </div>
                                            @endif

                                            @if($user->profession)
                                                <div class="user-data-row profession">
                                                    <span title="Профессия" data-toggle="tooltip" data-placement="right">
                                                        <i class="material-icons">school</i>
                                                        <span>
                                                            {{{ $user->profession }}}
                                                        </span>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-3 col-lg-4">
                                    <div class="points margin-bottom-10" title="Количество баллов" data-toggle="tooltip" data-placement="top">
                                        {{ Html::image('images/coins.png', '', ['class' => 'pull-left']) }}
                                        <span class="count pull-left">
                                            {{ $user->points }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="content">
                                @if($user->description)
                                    {{ StringHelper::addFancybox($user->description, 'group-content') }}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h2 id="honors">Награды</h2>

                            @if(count($user->userHonors))
                                <div id="message"></div>
                                @foreach($user->userHonors as $userHonor)
                                    @if(Auth::check())
                                        @if(Auth::user()->isAdmin())
                                            <div class="honor for-admin">
                                        @else
                                            <div class="honor">
                                        @endif
                                        @if(Auth::user()->isAdmin() && is_null($userHonor->honor->key))
                                            <a href="javascript:void(0)" class="remove-reward" data-honor-id="{{ $userHonor->honor->id }}">
                                                <i class="material-icons mdi-danger">cancel</i>
                                            </a>
                                        @endif
                                    @else
                                        <div class="honor">
                                    @endif
                                        <a href="{{ URL::route('honor.info', ['alias' => $userHonor->honor->alias]) }}">
                                            {{ $userHonor->honor->getImage(null, [
                                            'width' => '75px',
                                            'title' => !is_null($userHonor->comment)
                                                ? $userHonor->honor->title . ' ('. $userHonor->comment .')'
                                                : $userHonor->honor->title,
                                            'alt' => $userHonor->honor->title,
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'bottom'])
                                            }}
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                @if(Auth::check())
                                    @if(!Auth::user()->is($user))
                                        Нет наград.
                                    @else
                                        У Вас нет наград. Узнать о том, как можно получить награду, можно
                                        <a href="{{ URL::route('honors') }}">здесь</a>.
                                    @endif
                                @else
                                    Нет наград.
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                {{ $areaWidget->contentBottom() }}
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

    @if(Auth::check())
        @if(Auth::user()->isAdmin())
            <script type="text/javascript">
                $(".remove-reward").on('click', function() {
                    var $honor = $(this),
                            honorId = $honor.data('honorId');
                    if(confirm('Вы уверены, что хотите забрать награду у пользователя?')) {
                        $.ajax({
                            url: '<?php echo URL::route('admin.honors.removeReward') ?>',
                            dataType: "text json",
                            type: "POST",
                            data: {honor_id: honorId, user_id: '<?php echo $user->id ?>'},
                            beforeSend: function(request) {
                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                            },
                            success: function(response) {
                                if(response.success){
                                    $('#message').text(response.message);
                                    $honor.parent().remove();
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endif
@stop