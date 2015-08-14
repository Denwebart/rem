@extends('layouts.login')

<?php
$title = 'Восстановление пароля';
View::share('title', $title);
?>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 well">
                @if (Session::has('status'))
                    <div class="alert alert-success">
                        {{ Session::get('status') }}
                    </div>
                @elseif (Session::has('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif
                <h2>Восстановление пароля</h2>

                {{ Form::open(array('url' => action('RemindersController@postRemind'), 'method' => 'post', 'role' => 'form', 'class' => '')) }}

                    <div class="form-group">
                        {{ Form::text('email', '', ['class' => 'form-control floating-label', 'placeholder' => 'E-Mail*', 'autofocus'=>'autofocus']); }}
                        @if ($errors->has('email')) <p class="text-danger">{{ $errors->first('email') }}</p> @endif
                    </div>

                    <div class="form-group">
                        {{ Form::submit('Восстановить', ['id'=> 'submit', 'class' => 'btn btn-primary']) }}
                    </div>

                    {{ Form::hidden('_token', csrf_token()) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop