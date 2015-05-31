@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Создание рекламы <small>заполните все необходимые формы</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.advertising.index') }}">Реклама</a></li>
            <li>Создание рекламного блока</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            {{ Form::model($advertising, ['method' => 'POST', 'route' => ['admin.advertising.store']], ['id' => 'advertisingForm']) }}
                @include('admin::advertising._form')
            {{ Form::close() }}
        </div>
    </div>
@stop