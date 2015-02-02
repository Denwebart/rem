<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новый шаблон</title>
    {{ HTML::style('css/bootstrap.min.css') }}
</head>
<body>

@yield('content')

{{HTML::script('js/jquery-1.11.2.min.js')}}
{{HTML::script('js/bootstrap.min.js')}}
</body>
</html>