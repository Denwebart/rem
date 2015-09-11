@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои ответы' : 'Ответы пользователя ' . $user->login) : 'Ответы пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
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
                    {{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login }}
                </a>
            </li>
            <li class="hidden-md hidden-xs">{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <h2>{{ $title }}</h2>

                @if(Auth::check())
                    @if(Auth::user()->is($user))
                        @if($user->is_banned)
                            @include('cabinet::user.banMessage')
                        @elseif($headerWidget->isBannedIp)
                            @include('messages.bannedIp')
                        @endif
                    @endif
                @endif

                <div class="list">
                    @include('cabinet::user.answersList')
                </div>

            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    @if(Auth::check())
        @if(Auth::user()->is($user) || Auth::user()->isAdmin() || Auth::user()->isModerator())
            <script type="text/javascript">
                $(document).ready(function() {
                    $(".list").on('click', '.delete-answer', function() {
                        var $link = $(this);
                        var answerId = $link.data('id');
                        if(confirm('Вы уверены, что хотите удалить ответ?')) {
                            $.ajax({
                                url: "{{ URL::route('user.deleteAnswer', ['login' => $user->getLoginForUrl()]) }}",
                                dataType: "text json",
                                type: "POST",
                                data: {answerId: answerId},
                                beforeSend: function (request) {
                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                },
                                success: function (response) {
                                    if (response.success) {
                                        $('#content .list').html(response.answersList);
                                        $('#site-messages').prepend(response.message);
                                    }
                                }
                            });
                        }
                    });
                });
            </script>
        @endif
    @endif
@endsection