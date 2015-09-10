@extends('layouts.error')

<?php
$title = '404 ошибка: страница не найдена';
View::share('title', $title);
?>

@section('content')
    <div class="error">
        <span class="error-code">
            404
            <span class="hidden-md hidden-sm hidden-xs">ошибка</span>
        </span>
        <p class="error-message">Страница не найдена</p>
    </div>
@endsection