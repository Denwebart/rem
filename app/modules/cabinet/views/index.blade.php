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

        @if(count($users))
            <div id="users">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-2">
                        {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статьи', 'publishedArticles') }}
                    </div>
                    <div class="col-md-2">
                        {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Вопросы', 'publishedQuestions') }}
                    </div>
                    <div class="col-md-2">
                        {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Ответы', 'publishedAnswers') }}
                    </div>
                    <div class="col-md-2">
                        {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Комменатрии', 'publishedComments') }}
                    </div>
                    <div class="col-md-2">
                        {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Награды', 'honors') }}
                    </div>
                </div>

                @foreach($users as $user)

                    <div class="row margin-bottom-20">
                        <div class="col-md-2">
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                {{ $user->getAvatar('mini') }}
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                {{ $user->login }}
                            </a>
                            @if($user->getFullName())
                                <p>{{ $user->getFullName() }}</p>
                            @endif

                        </div>
                        <div class="col-md-2">
                            <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()]) }}">
                                {{ count($user->publishedArticles) }}
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}">
                                {{ count($user->publishedQuestions) }}
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ URL::route('user.answers', ['login' => $user->getLoginForUrl()]) }}">
                                {{ count($user->publishedAnswers) }}
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}">
                                {{ count($user->publishedComments) }}
                            </a>
                        </div>
                        <div class="col-md-2">
                            @foreach($user->honors as $honor)
                                <a href="{{ URL::route('honor.info', ['alias' => $honor->alias]) }}">
                                    {{ $honor->getImage(null, ['width' => '25px', 'title' => $honor->title, 'alt' => $honor->title]) }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                @endforeach

{{--                {{ $users->links() }}--}}
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