@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Создание страницы <small>заполните все необходимые формы</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.pages.index') }}">Страницы</a></li>
            <li>Создание страницы</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            {{ Form::model($page, ['method' => 'POST', 'route' => ['admin.pages.store'], 'id' => 'pagesForm', 'files' => true]) }}
                @include('admin::pages._form')
            {{ Form::close() }}
        </div>
    </div>
@stop