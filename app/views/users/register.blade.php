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
                        <div class="form-group @if($errors->has('login')) has-error @endif">
                            {{ Form::text('login', null, array('class' => 'form-control floating-label', 'placeholder' => 'Логин*', 'autofocus'=>'autofocus')) }}
                            @if ($errors->has('login'))
                                <small class="text-danger">{{ $errors->first('login') }}</small>
                            @elseif ($errors->has('alias'))
                                <small class="text-danger">{{ $errors->first('alias') }}</small>
                            @endif
                        </div>
                        <div class="form-group @if($errors->has('email')) has-error @endif">
                            {{ Form::text('email', null, array('class' => 'form-control floating-label', 'placeholder' => 'E-Mail*')) }}
                            @if ($errors->has('email')) <small class="text-danger">{{ $errors->first('email') }}</small> @endif
                        </div>
                        <div class="form-group @if($errors->has('password')) has-error @endif">
                            {{ Form::password('password', array('class' => 'form-control floating-label', 'placeholder' => 'Пароль*')) }}
                            @if ($errors->has('password')) <small class="text-danger">{{ $errors->first('password') }}</small> @endif
                        </div>
                        <div class="form-group @if($errors->has('password_confirmation')) has-error @endif">
                            {{ Form::password('password_confirmation', array('class' => 'form-control floating-label', 'placeholder' => 'Повтор пароля*')) }}
                            @if ($errors->has('password_confirmation')) <small class="text-danger">{{ $errors->first('password_confirmation') }}</small> @endif
                        </div>
                        <div class="form-group @if($errors->has('g-recaptcha-response')) has-error @endif">
                            {{ Form::captcha() }}
                            @if ($errors->has('g-recaptcha-response'))
                                <div class="clearfix"></div>
                                <small class="text-danger">{{ $errors->first('g-recaptcha-response') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="row margin-bottom-10">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <small class="text-muted">
                                        Указывайте ваш настоящий email-адрес,
                                        так как на этот ящик будет выслано письмо с подтверждением регистрации.
                                    </small>
                                </div>
                            </div>
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