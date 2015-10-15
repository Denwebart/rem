@extends('admin::layouts.admin')

<?php
$title = 'Меню сайта';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-exclamation-triangle"></i>
                    {{ $title }}
                    <small></small>
                </h1>
            </div>
        </div>

        {{--<ol class="breadcrumb">--}}
            {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
            {{--<li><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>--}}
            {{--<li class="active">{{ $title }}</li>--}}
        {{--</ol>--}}
    </div>
    <div class="content">
        <!-- Main row -->
        <div class="row">

            <div class="col-md-10 col-sm-9 col-xs-12 margin-bottom-15">
                <a href="{{ URL::route('admin.settings.index') }}" class="btn btn-dashed">
                    <span>Все настройки</span>
                </a>
                <a href="{{ URL::route('admin.rules.index') }}" class="btn btn-dashed">
                    <span>Правила сайта</span>
                </a>
                <a href="{{ URL::route('admin.notificationsMessages.index') }}" class="btn btn-dashed">
                    <span>Шаблоны уведомлений</span>
                </a>
                <a href="{{ URL::route('admin.menus.index') }}" class="btn btn-primary">
                    <span>Меню сайта</span>
                </a>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12 margin-bottom-15">
            </div>

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Тип меню
                                    </th>
                                    <th>
                                        Количество страниц в меню
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="menus-list">
                                @foreach($menus as $menu)
                                    <tr onclick="window.location.href='{{ URL::route('admin.menus.items', ['type' => $menu->type]) }}'; return false" class="link">
                                        <td>{{ Menu::$types[$menu->type] }}</td>
                                        <td>{{ $menu->pagesCount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </div>
@stop