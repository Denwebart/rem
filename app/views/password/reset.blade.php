@extends('layouts.login')

<?php
$title = 'Сброс пароля';
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
                @if (Session::has('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif

                <h2>Сброс пароля</h2>

                {{ Form::open(array('url' => action('RemindersController@postReset'), 'method' => 'post', 'role' => 'form', 'class' => '')) }}

                <div class="form-group @if($errors->has('email')) has-error @endif">
                    {{ Form::text('email', '', ['class' => 'form-control floating-label', 'placeholder' => 'E-Mail*', 'autofocus'=>'autofocus']); }}
                    @if ($errors->has('email')) <small class="text-danger">{{ $errors->first('email') }}</small> @endif
                </div>

                <div class="form-group @if($errors->has('password')) has-error @endif">
                    {{ Form::password('password', ['class' => 'form-control floating-label', 'placeholder' => 'Новый пароль*']); }}
                    @if ($errors->has('password')) <small class="text-danger">{{ $errors->first('password') }}</small> @endif
                </div>

                <div class="form-group @if($errors->has('password_confirmation')) has-error @endif">
                    {{ Form::password('password_confirmation', ['class' => 'form-control floating-label', 'placeholder' => 'Повторите пароль*']); }}
                    @if ($errors->has('password_confirmation')) <small class="text-danger">{{ $errors->first('password_confirmation') }}</small> @endif
                </div>

                {{ Form::hidden('token', $token) }}

                <div class="form-group">
                    <div class="col-lg-8 col-md-7 col-sm-7">
                        {{ Form::submit('Сбросить', ['id'=> 'submit', 'class' => 'btn btn-primary btn-full']) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop