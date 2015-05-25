@extends('cabinet::layouts.honors')

<?php
$title = $honor->title;
View::share('page', $title);
?>

@section('content')
    <section id="content">
        <h2>{{ $honor->title }}</h2>

        {{ $honor->getImage() }}

        {{ $honor->description }}

        <h3>Пользователи, у которых есть эта награда</h3>

        @if(count($honor->users))
            <ul class="users">
                @foreach($honor->users as $user)
                    <li>
                        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                            {{ $user->getAvatar('mini') }}
                            {{ $user->login }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Еще ни у кого нет этой награды</p>
        @endif

    </section>
@stop
