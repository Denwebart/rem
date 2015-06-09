@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Редактирование рекламы <small>редактирование рекламного блока</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.advertising.index') }}">Реклама</a></li>
            <li>Редактирование {{ $advertising->title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">{{ $advertising->title }}</h4>
            </div>
            {{ Form::model($advertising, ['method' => 'PUT', 'route' => ['admin.advertising.update', $advertising->id], 'id' => 'advertisingForm']) }}
            @include('admin::advertising._form')
            {{ Form::close() }}
        </div>
    </div>
@stop