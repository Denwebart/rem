{{--@extends('layouts.users')--}}

{{--<?php--}}
{{--$title = 'Все пользователи';--}}
{{--View::share('page', $title);--}}
{{--?>--}}

{{--@section('content')--}}
    {{--<section id="content">--}}
        {{--<h2>{{ $title }}</h2>--}}

        {{--{{ Form::open(['method' => 'GET', 'route' => ['users.search'], 'files' => true], ['id' => 'searchUsers']) }}--}}

        {{--<div class="col-md-10">--}}
            {{--<div class="form-group">--}}
                {{--{{ Form::text('name', $name, ['class' => 'form-control']) }}--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-md-2">--}}
            {{--{{ Form::submit('Найти', ['class' => 'btn btn-success']) }}--}}
        {{--</div>--}}

        {{--{{ Form::close() }}--}}

        {{--<div id="users">--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-2"></div>--}}
                {{--<div class="col-md-4"></div>--}}
                {{--<div class="col-md-2">Статьи</div>--}}
                {{--<div class="col-md-2">Вопросы</div>--}}
                {{--<div class="col-md-2">Комменатрии</div>--}}
            {{--</div>--}}

            {{--@foreach($users as $user)--}}

                {{--<div class="row margin-bottom-20">--}}
                    {{--<div class="col-md-2">--}}
                        {{--<a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">--}}
                            {{--{{ $user->getAvatar('mini') }}--}}
                        {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-4">--}}
                        {{--<a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">--}}
                            {{--{{ $user->login }}--}}
                        {{--</a>--}}
                        {{--@if($user->getFullName())--}}
                            {{--<p>{{ $user->getFullName() }}</p>--}}
                        {{--@endif--}}

                    {{--</div>--}}
                    {{--<div class="col-md-2"></div>--}}
                    {{--<div class="col-md-2"></div>--}}
                    {{--<div class="col-md-2">--}}
                        {{--<a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}">--}}
                            {{--{{ count($user->publishedСomments) }}--}}
                        {{--</a>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--@endforeach--}}

            {{--{{ $users->links() }}--}}

        {{--</div>--}}
    {{--</section>--}}
{{--@stop--}}