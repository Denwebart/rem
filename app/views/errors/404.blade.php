@extends('layouts.error')

<?php
$title = '404 ошибка: страница не найдена';
View::share('title', $title);
?>

@section('content')
    <div class="error error-404">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                {{ HTML::image('images/avtorem-404.png', '', ['class' => 'img-responsive']) }}
            </div>
            <div class="col-md-5 col-sm-6">
                <p class="error-message">Страница не найдена.</p>
                <p>Cтраница, которую Вы запрашиваете, не существует.</p>
                <p>Воспользуйтесь меню сайта или поиском, чтобы найти нужную вам страницу.</p>

                @include('search')
            </div>
        </div>
    </div>
@stop