@extends('layouts.login')

<?php
$title = 'Регистрация';
View::share('title', $title);
?>

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 well">

                <h2>Регистрация</h2>

                {{ Form::open(['url' => 'register_request', 'role' => 'form', 'class' => '']) }}

                    <div class="form-group">
                        {{ Form::text('login', null, array('class' => 'form-control floating-label', 'placeholder' => 'Логин', 'autofocus'=>'autofocus')) }}
                        @if ($errors->has('login')) <p class="text-danger">{{ $errors->first('login') }}</p> @endif
                    </div>

                    <div class="form-group">
                        {{ Form::text('email', null, array('class' => 'form-control floating-label', 'placeholder' => 'E-Mail')) }}
                        @if ($errors->has('email')) <p class="text-danger">{{ $errors->first('email') }}</p> @endif
                    </div>

                    <div class="form-group">
                        {{ Form::password('password', array('class' => 'form-control floating-label', 'placeholder' => 'Пароль')) }}
                        @if ($errors->has('password')) <p class="text-danger">{{ $errors->first('password') }}</p> @endif
                    </div>

                    <div class="form-group">
                        {{ Form::password('password_confirmation', array('class' => 'form-control floating-label', 'placeholder' => 'Повтор пароля')) }}
                        @if ($errors->has('password_confirmation')) <p class="text-danger">{{ $errors->first('password_confirmation') }}</p> @endif
                    </div>

                    <div class="form-group">
                        <div class="col-sm-5 col-sm-offset-2">
                            {{ Form::submit('Зарегистрироваться', ['id'=> 'submit', 'class' => 'btn btn-primary']) }}
                        </div>
                    </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop