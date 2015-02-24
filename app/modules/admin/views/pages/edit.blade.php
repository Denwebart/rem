@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Редактирование страницы <small>редактирование данных страницы</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.pages.index') }}">Страницы</a></li>
            <li>{{ Str::limit($page->getTitle(), 60, '...') }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">{{ $page->getTitle() }}</h4>
            </div>
            {{ Form::model($page, array('method' => 'PUT', 'route' => array('admin.pages.update', $page->id)), ['id' => 'registerForm']) }}
                @include('admin::pages._form')
            {{ Form::close() }}
        </div>
    </div>
@stop