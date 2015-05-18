@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Редактирование награды <small>редактирование награды</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.honors.index') }}">Награды</a></li>
            <li>Редактирование награды</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            {{ Form::model($honor, ['method' => 'PUT', 'route' => ['admin.honors.update', $honor->id], 'id' => 'honorsForm', 'files' => true]) }}
            @include('admin::honors._form')
            {{ Form::close() }}
        </div>
    </div>
@stop