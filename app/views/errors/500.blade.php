@extends('layouts.error')

<?php
$title = '500 ошибка';
View::share('title', $title);
?>

@section('content')
    <div class="error">
        <span class="error-code">
            500
            <span class="hidden-md hidden-sm hidden-xs">ошибка</span>
        </span>
    </div>
@stop