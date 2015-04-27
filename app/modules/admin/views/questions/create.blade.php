@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Создание вопроса <small>заполните все необходимые формы</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.questions.index') }}">Вопросы</a></li>
            <li>Создание вопроса</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            {{ Form::model($page, ['method' => 'POST', 'route' => ['admin.questions.store']], ['id' => 'registerForm']) }}
                @include('admin::questions._form')
            {{ Form::close() }}
        </div>
    </div>
@stop