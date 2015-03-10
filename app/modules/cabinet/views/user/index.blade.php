@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>Мой профиль</li>
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

            <a href="{{{ URL::route('user.edit', ['login' => $user->login]) }}}" class="pull-right">
                <span class="glyphicon glyphicon-edit"></span>
                Редактировать
            </a>
            <h2>{{{ $user->login }}}</h2>
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

            @if($user->profession)
                <p>{{ $user->description }}</p>
            @endif

        </div>
    </div>
@stop