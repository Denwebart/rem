@extends('layouts.users')

<?php
$title = 'Все пользователи';
View::share('page', $title);
?>

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li>{{ $title }}</li>
    </ol>

    <section id="content">
        <h2>{{ $title }}</h2>

        <div class="row">
            {{ Form::open(['method' => 'GET', 'route' => ['users'], 'id' => 'search-users-form']) }}

                <div class="col-md-10">
                    <div class="form-group">
                        {{ Form::text('name', $name, ['class' => 'form-control', 'id' => 'name']) }}
                    </div>
                </div>
                <div class="col-md-2">
                    {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
                </div>
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>

        <div class="row">
            @if(count($users))
                <div id="users" class="col-md-12">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Зарегистрирован', 'created_at') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статьи', 'publishedArticles') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Вопросы', 'publishedQuestions') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Ответы', 'publishedAnswers') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Комменатрии', 'publishedComments') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Награды', 'honors') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Баллы', 'points') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                            {{ $user->getAvatar('mini') }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                            {{ $user->login }}
                                        </a>
                                        @if($user->getFullName())
                                            <p>{{ $user->getFullName() }}</p>
                                        @endif
                                        @if($user->isAdmin() || $user->isModerator())
                                            <span>{{ User::$roles[$user->role] }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ date('d/m/Y', strtotime($user->created_at)) }}
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()]) }}">
                                            {{ count($user->publishedArticles) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}">
                                            {{ count($user->publishedQuestions) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('user.answers', ['login' => $user->getLoginForUrl()]) }}">
                                            {{ count($user->publishedAnswers) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}">
                                            {{ count($user->publishedComments) }}
                                        </a>
                                    </td>
                                    <td>
                                        Награды:
                                        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}#honors">
                                            {{ count($user->honors) }}
                                        </a>
                                        @foreach($user->honors as $honor)
                                            <a href="{{ URL::route('honor.info', ['alias' => $honor->alias]) }}">
                                                {{ $honor->getImage(null, ['width' => '25px', 'title' => $honor->title, 'alt' => $honor->title]) }}
                                            </a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $user->points }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
{{--                    {{ $users->links() }}--}}
                </div>
            </div>
        @else
            <p>Пользователь не найден.</p>
        @endif

       `{{ $areaWidget->contentBottom() }}
    </section>
@stop

@section('style')
    @parent
    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
    <script src="/js/jquery-ui.min.js"></script>
@stop

@section('script')
    @parent

    <script type="text/javascript">
        $("#name").autocomplete({
            source: "<?php echo URL::route('users.autocomplete') ?>",
            minLength: 1,
            select: function(e, ui) {
                $("#search-users-form #name").val(ui.item.value);
                $("#search-users-form").submit();
            }
        });
    </script>

@stop