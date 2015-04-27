@extends('admin::layouts.admin')

<?php
$title = '404 ошибка: страница не найдена';
View::share('title', $title);
View::share('headerWidget', app('HeaderWidget'));
?>

@section('content')
    <div style="text-align: center; width: 100%">
        <h1 style="font-size: 200px">404 ошибка</h1>
        <p style="font-size: 40px">Страница не найдена</p>
    </div>
@endsection