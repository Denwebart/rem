@extends('admin.layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Просмотр страницы "{{ $page->getTitle() }}" <small>информация о странице</small></h1>
        <ol class="breadcrumb">
            <li>Вы находитесь здесь:</li>
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.pages.index') }}">Страницы</a></li>
            <li>Просмотр страницы "{{ $page->getTitle() }}"</li>
        </ol>
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">

            </div>
        </div>
    </div>
@stop
