@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li>Мои вопросы</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                @if($user->avatar)
                    {{ HTML::image('/uploads/' . $user->login . '/' . $user->avatar, $user->login, ['class' => 'img-responsive']) }}
                @else
                    {{ HTML::image(Config::get('settings.defaultAvatar'), $user->login, ['class' => 'img-responsive avatar-default']) }}
                @endif
            </div>
        </div>
        <div class="col-lg-9">
            <h2>Вопросы {{ $user->login }}</h2>

        </div>
    </div>
@stop