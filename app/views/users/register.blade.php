@extends('layouts.login')

<?php
$title = 'Регистрация';
View::share('title', $title);
?>

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 hidden-xs">
                <a href="{{ URL::to('/') }}">
                    {{ HTML::image('images/logo.png', '', ['class' => 'img-responsive margin-bottom-20']) }}
                </a>
            </div>
            <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 well">

                <h2>Регистрация</h2>

                @if(!Ip::isBanned())
                    {{ Form::open(['url' => 'register_request', 'role' => 'form', 'class' => '']) }}
                        <div class="form-group">
                            {{ Form::text('login', null, array('class' => 'form-control floating-label', 'placeholder' => 'Логин*', 'autofocus'=>'autofocus')) }}
                            @if ($errors->has('login'))
                                <p class="text-danger">{{ $errors->first('login') }}</p>
                            @elseif ($errors->has('alias'))
                                <p class="text-danger">{{ $errors->first('alias') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            {{ Form::text('email', null, array('class' => 'form-control floating-label', 'placeholder' => 'E-Mail*')) }}
                            @if ($errors->has('email')) <p class="text-danger">{{ $errors->first('email') }}</p> @endif
                        </div>
                        <div class="form-group">
                            {{ Form::password('password', array('class' => 'form-control floating-label', 'placeholder' => 'Пароль*')) }}
                            @if ($errors->has('password')) <p class="text-danger">{{ $errors->first('password') }}</p> @endif
                        </div>
                        <div class="form-group">
                            {{ Form::password('password_confirmation', array('class' => 'form-control floating-label', 'placeholder' => 'Повтор пароля*')) }}
                            @if ($errors->has('password_confirmation')) <p class="text-danger">{{ $errors->first('password_confirmation') }}</p> @endif
                        </div>
                        <div class="form-group">
                            {{ Form::captcha() }}
                            @if ($errors->has('g-recaptcha-response'))
                                <p class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-8 col-md-7 col-sm-7">
                                    {{ Form::submit('Зарегистрироваться', ['id'=> 'submit', 'class' => 'btn btn-primary btn-full']) }}
                                </div>
                                <div class="col-lg-4 col-md-5 col-sm-5">
                                    <a href="{{ URL::route('login') }}">Войти</a>
                                </div>
                            </div>
                        </div>
                        {{ Form::hidden('_token', csrf_token()) }}
                    {{ Form::close() }}
                @else
                    @include('messages.bannedIp')
                @endif
            </div>
        </div>
    </div>
@stop