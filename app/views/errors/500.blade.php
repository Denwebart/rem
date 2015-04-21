@extends('layouts.error')

<?php
$title = '500 ошибка';
View::share('title', $title);
?>

@section('content')
    <div style="text-align: center; width: 100%">
        <h1 style="font-size: 200px">500 ошибка</h1>
    </div>
@endsection