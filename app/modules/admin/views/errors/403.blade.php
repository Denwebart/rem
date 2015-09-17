@extends('admin::layouts.admin')

<?php
$title = '403 ошибка: нет прав для просмотра этой страницы';
View::share('title', $title);
View::share('headerWidget', app('HeaderWidget'));
?>

@section('content')
    <div style="text-align: center; width: 100%">
        <h1 style="font-size: 200px">403</h1>
        <p style="font-size: 40px">У Вас нет прав для просмотра этой страницы.</p>
    </div>
@stop