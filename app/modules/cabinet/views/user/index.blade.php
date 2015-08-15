@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-md-12" id="content profile">
                <div class="row">
                    <div class="col-md-8">

                        <!-- всплывающее сообщение - согласие с правилами сайта -->
                        @if(Session::has('rulesSuccessMessage'))
                            @section('siteMessages')
                                @include('widgets.siteMessages.success', ['siteMessage' => Session::get('rulesSuccessMessage')])
                                @parent
                            @endsection
                        @endif

                        <!-- всплывающее сообщение - пароль изменен -->
                        @if(Session::has('successMessage'))
                            @section('siteMessages')
                                @include('widgets.siteMessages.success', ['siteMessage' => Session::get('successMessage')])
                                @parent
                            @endsection
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

                        <h2>{{{ $user->login }}}</h2>

                        <p class="date date-register">
                            <i class="material-icons pull-left">today</i>
                            <span title="Дата регистрации" data-toggle="tooltip" data-placement="top">
                                {{{ DateHelper::dateFormat($user->created_at) }}}
                            </span>
                        </p>

                        @if(Auth::check())
                            @if(Auth::user()->is($user) || Auth::user()->isAdmin() || Auth::user()->isModerator())
                                <p class="email">{{{ $user->email }}}</p>
                            @endif
                        @endif

                        @if($user->isAdmin() || $user->isModerator())
                            <p>{{ User::$roles[$user->role] }}</p>
                        @endif

                        @if($user->getFullName())
                            <h3>{{{ $user->getFullName() }}}</h3>
                        @endif

                        @if($user->country)
                            <p>{{{ $user->country }}}</p>
                        @endif

                        @if($user->city)
                            <p>{{{ $user->city }}}</p>
                        @endif

                        @if($user->car_brand)
                            <p>{{{ $user->car_brand }}}</p>
                        @endif

                        @if($user->profession)
                            <p>{{{ $user->profession }}}</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                @if(Auth::check())
                                    @if((Auth::user()->is($user) && !$headerWidget->isBannedIp && !$user->is_banned) || Auth::user()->isAdmin())
                                        <a href="{{{ URL::route('user.edit', ['login' => $user->getLoginForUrl()]) }}}" class="pull-right">
                                            Редактировать
                                            <i class="material-icons">mode_edit</i>
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-12">
                                @if(Auth::check())
                                    @if(Auth::user()->is($user))
                                        <a href="{{{ URL::route('user.changePassword', ['login' => $user->getLoginForUrl()]) }}}" class="pull-right">
                                            Изменить пароль
                                            <i class="material-icons">security</i>
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="well margin-top-20">
                                    <h4>
                                        Баллов:
                                        <span class="count">
                                            {{ $user->points }}
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        @if($user->description)
                            <p>{{ $user->description }}</p>
                        @endif
                    </div>
                    <div class="col-md-12">
                        <h2 id="honors">Награды</h2>

                        @if(count($user->userHonors))
                            <div id="message"></div>
                            @foreach($user->userHonors as $userHonor)
                                <div class="honor">
                                    <a href="{{ URL::route('honor.info', ['alias' => $userHonor->honor->alias]) }}">
                                        {{ $userHonor->honor->getImage(null, [
                                        'width' => '75px',
                                        'title' => !is_null($userHonor->comment)
                                            ? $userHonor->honor->title . ' ('. $userHonor->comment .')'
                                            : $userHonor->honor->title,
                                        'alt' => $userHonor->honor->title
                                        ]) }}
                                    </a>
                                    @if(Auth::check())
                                        @if(Auth::user()->isAdmin() && is_null($userHonor->honor->key))
                                            <a href="javascript:void(0)" class="remove-reward" data-honor-id="{{ $userHonor->honor->id }}">
                                                <i class="material-icons mdi-danger">cancel</i>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        @else
                            @if(Auth::check())
                                @if(!Auth::user()->is($user))
                                    Нет наград.
                                @else
                                    У Вас нет наград. Узнать о том, как можно получить награду, можно
                                    <a href="">здесь</a>.
                                @endif
                            @else
                                Нет наград.
                            @endif
                        @endif
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