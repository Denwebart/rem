@extends('layouts.login')

<?php
$title = 'Регистрация почти завершена.';
View::share('title', $title);
?>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 well">

                <h2>Регистрация</h2>

                <div class="alert alert-{{ $status }}">{{ $message }}</div>

                @if ($redirect)
                    <script type="application/javascript">
                        setTimeout(
                            function() {
                                location.href = '{{ $redirect }}';
                            },
                            50000
                        );
                    </script>
                    <p class="like-h">
                        Нажмите <a href="{{ $redirect }}">эту ссылку</a>,
                        если ваш браузер не поддерживает автоматический редирект.
                    </p>
                @endif

            </div>
        </div>
    </div>
@stop