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
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        {{ Form::text('name', $name, ['class' => 'form-control', 'id' => 'name']) }}
                    </div>
                </div>
                <div class="col-md-2">
                    {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::select('interval', User::$intervals, Request::get('interval'), ['class' => 'form-control', 'id' => 'interval']) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group month" @if(!Request::has('interval') || User::INTERVAL_ALL_TIMES == Request::get('interval')) style="display: none" @endif>
                        {{ Form::select('month', DateHelper::$monthsList, Request::has('month') ? Request::get('month') : date('n'), ['class' => 'form-control', 'id' => 'month']) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group year" @if(!Request::has('interval') || User::INTERVAL_ALL_TIMES == Request::get('interval')) style="display: none" @endif>
                        {{ Form::selectYear('year', 2015, date('Y'), Request::has('year') ? Request::get('year') : date('Y'), ['class' => 'form-control', 'id' => 'year']) }}
                    </div>
                </div>
                {{ Form::hidden('direction', Request::get('direction')) }}
                {{ Form::hidden('sortBy', Request::get('sortBy')) }}
            </div>
        {{ Form::close() }}

        <div class="row">
            @if(count($users))
                <div id="users" class="col-md-12">
                    <table class="table table-striped table-hover">
                        <?php
                            $parameters = [];
                            if(Request::has('name')) {
                                $parameters['name'] = Request::get('name');
                            }
                            if(Request::has('interval')) {
                                $parameters['interval'] = Request::get('interval');
                            }
                            if(Request::has('month')) {
                                $parameters['month'] = Request::get('month');
                            }
                            if(Request::has('year')) {
                                $parameters['year'] = Request::get('year');
                            }
                        ?>
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Зарегистрирован', 'created_at', $parameters) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статьи', 'publishedArticles', $parameters) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Вопросы', 'publishedQuestions', $parameters) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Ответы', 'publishedAnswers', $parameters) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Комменатрии', 'publishedComments', $parameters) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Награды', 'honors', $parameters) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Баллы', 'points', $parameters) }}
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
                                        {{ DateHelper::dateFormat($user->created_at, false) }}
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

       {{ $areaWidget->contentBottom() }}
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
        $(document).ready(function() {

            // автокомплит пользователей
            $("#name").autocomplete({
                source: "<?php echo URL::route('users.autocomplete') ?>",
                minLength: 1,
                select: function(e, ui) {
                    $("#search-users-form #name").val(ui.item.value);
                    $("#search-users-form").submit();
                }
            });

            // отправка формы после выбора
            $("select[id^='interval'], select[id^='month'], select[id^='year']").on('change', function(){
                $("#search-users-form").submit();
            });

            // исключение пустых полей формы
            $("#search-users-form").submit(function() {
                if($("#name").val() == "") {
                    $("#name").prop("disabled", true);
                }
                if($("#interval").val() == '<?php echo User::INTERVAL_ALL_TIMES ?>') {
                    $("#month").prop("disabled", true);
                    $("#year").prop("disabled", true);
                }
                if($("#month").val() == 0) {
                    $("#month").prop("disabled", true);
                }
                if($("#year").val() == 0) {
                    $("#year").prop("disabled", true);
                }
                if($("[name='direction']").val() == 0) {
                    $("[name='direction']").prop("disabled", true);
                }
                if($("[name='sortBy']").val() == 0) {
                    $("[name='sortBy']").prop("disabled", true);
                }
            });
        });
    </script>

@stop