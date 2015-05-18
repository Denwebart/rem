@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Создание награды <small>создание новой награды</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.honors.index') }}">Награды</a></li>
            <li>Создание награды</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            {{ Form::model($honor, ['method' => 'POST', 'route' => ['admin.honors.store'], 'id' => 'honorsForm', 'files' => true]) }}
            @include('admin::honors._form')
            {{ Form::close() }}
        </div>
    </div>
@stop