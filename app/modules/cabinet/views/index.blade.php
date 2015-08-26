@extends('cabinet::layouts.users')

<?php
$title = 'Все пользователи';
View::share('page', $title);

$bestWriter = User::getBestWriter(null, null, 1);
$bestRespondent = User::getBestRespondent(null, null, 1);
$bestCommentator = User::getBestCommentator(null, null, 1);
?>

@section('leftSidebar')
    @if(count($bestWriter) || count($bestRespondent) || count($bestCommentator))
        <div id="leaders-sidebar-widget" class="list-group sidebar-widget">
            <h4>Лидеры месяца
                <span class="pull-right">
                    <?php $lastMonth = date_create(date('d-m-Y') . ' first day of last month'); ?>
                    {{ DateHelper::$monthsList[$lastMonth->format('n')] }}
                    {{ $lastMonth->format('Y') }}
                </span>
            </h4>

            @foreach($bestWriter as $key => $user)
                @if($key == 0)
                    <div class="award">
                        <div class="row-user">
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="avatar-link display-inline-block">
                                {{ $user->getAvatar('mini', ['class' => 'avatar circle']) }}
                                @if($user->isOnline())
                                    <span class="is-online-status online" title="Сейчас на сайте"></span>
                                @else
                                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}"></span>
                                @endif
                            </a>
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="login">
                                {{ $user->login }}
                            </a>
                        </div>
                        <div class="row-content">
                            <p>Баллов: {{ $user->articlesPoints }}</p>
                            <p>Статей: {{ $user->articlesCount }}</p>
                        </div>
                        <div class="row-title">
                            <h5>Лучший писатель</h5>
                        </div>
                    </div>
                @else
                    <div class="list-group-item">
                        <div class="row-picture">
                            {{--{{ $key + 1 }}.--}}
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="avatar-link gray-background">
                                {{ $user->getAvatar('mini', ['class' => 'avatar circle']) }}
                                @if($user->isOnline())
                                    <span class="is-online-status online" title="Сейчас на сайте"></span>
                                @else
                                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}"></span>
                                @endif
                            </a>
                        </div>
                        <div class="row-content">
                            <p class="list-group-item-text" style="clear: both">
                                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                    {{ $user->login }}
                                </a>
                                <br/>
                                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                    {{ $user->getFullName() }}
                                </a>
                            </p>
                            <p>Баллы за статьи: {{ $user->articlesPoints }}</p>
                            <p>Количество статей: {{ $user->articlesCount }}</p>
                        </div>
                    </div>
                @endif
            @endforeach

            @foreach($bestRespondent as $key => $user)
                @if($key == 0)
                    <div class="award">
                        <div class="row-user">
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="avatar-link display-inline-block">
                                {{ $user->getAvatar('mini', ['class' => 'avatar circle']) }}
                                @if($user->isOnline())
                                    <span class="is-online-status online" title="Сейчас на сайте"></span>
                                @else
                                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}"></span>
                                @endif
                            </a>
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="login">
                                {{ $user->login }}
                            </a>
                        </div>
                        <div class="row-content">
                            <p>Баллов: {{ $user->answersPoints }}</p>
                            <p>Ответов: {{ $user->answersCount }}, лучших: {{ $user->countBestAnswers }}</p>
                        </div>
                        <div class="row-title">
                            <h5>Лучший советчик</h5>
                        </div>
                    </div>
                @else
                    <div class="list-group-item">
                        <div class="row-picture">
                            {{--{{ $key + 1 }}.--}}
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="avatar-link gray-background">
                                {{ $user->getAvatar('mini', ['class' => 'avatar circle']) }}
                                @if($user->isOnline())
                                    <span class="is-online-status online" title="Сейчас на сайте"></span>
                                @else
                                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}"></span>
                                @endif
                            </a>
                        </div>
                        <div class="row-content">
                            <p class="list-group-item-text" style="clear: both">
                                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                    {{ $user->login }}
                                </a>
                                <br/>
                                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                    {{ $user->getFullName() }}
                                </a>
                            </p>
                            <p>Баллов: {{ $user->answersPoints }}</p>
                            <p>Ответов: {{ $user->answersCount }}, лучших: {{ $user->countBestAnswers }}</p>
                        </div>
                    </div>
                @endif
            @endforeach

            @foreach($bestCommentator as $key => $user)
                @if($key == 0)
                    <div class="award">
                        <div class="row-user">
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="avatar-link display-inline-block">
                                {{ $user->getAvatar('mini', ['class' => 'avatar circle']) }}
                                @if($user->isOnline())
                                    <span class="is-online-status online" title="Сейчас на сайте"></span>
                                @else
                                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}"></span>
                                @endif
                            </a>
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="login">
                                {{ $user->login }}
                            </a>
                        </div>
                        <div class="row-content">
                            <p>Баллов: {{ $user->commentsPoints }}</p>
                            <p>Комментариев: {{ $user->commentsCount }}</p>
                        </div>
                        <div class="row-title">
                            <h5>Лучший комментатор</h5>
                        </div>
                    </div>
                @else
                    <div class="list-group-item">
                        <div class="row-picture">
                            {{--{{ $key + 1 }}.--}}
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="avatar-link gray-background">
                                {{ $user->getAvatar('mini', ['class' => 'avatar circle']) }}
                                @if($user->isOnline())
                                    <span class="is-online-status online" title="Сейчас на сайте"></span>
                                @else
                                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}"></span>
                                @endif
                            </a>
                        </div>
                        <div class="row-content">
                            <p class="list-group-item-text" style="clear: both">
                                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                    {{ $user->login }}
                                </a>
                                <br/>
                                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                    {{ $user->getFullName() }}
                                </a>
                            </p>
                            <p>Баллы за комментарии: {{ $user->commentsPoints }}</p>
                            <p>Количество комментариев: {{ $user->commentsCount }}</p>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    @endif
@endsection

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li>{{ $title }}</li>
    </ol>

    <section id="content">
        <h2>{{ $title }}</h2>

        {{ Form::open(['method' => 'GET', 'route' => ['users'], 'id' => 'search-users-form']) }}
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        {{ Form::text('name', $name, ['class' => 'form-control', 'id' => 'name']) }}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('is_online', 1, $is_online, ['id' => 'is-online']); }}
                            Только онлайн
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="material-icons">search</i>
                        <span>Найти</span>
                        <div class="ripple-wrapper"></div>
                    </button>
                </div>
            </div>
        {{ Form::close() }}

        <div class="row">
            @if(count($users))
                <div id="users" class="col-md-12">
                    <div class="well">
                        <table class="table table-hover">
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
                                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="avatar-link display-inline-block">
                                                {{ $user->getAvatar('mini', ['class' => 'avatar circle']) }}
                                                @if($user->isOnline())
                                                    <span class="is-online-status online" title="Сейчас на сайте"></span>
                                                @else
                                                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}"></span>
                                                @endif
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
                                                {{ count($user->userHonors) }}
                                            </a>
                                            <div class="honors">
                                                @foreach($user->userHonors as $key => $userHonor)
                                                    @if($key < 3)
                                                        <a href="{{ URL::route('honor.info', ['alias' => $userHonor->honor->alias]) }}">
                                                            {{ $userHonor->honor->getImage(null, [
                                                                'width' => '25px',
                                                                'title' => !is_null($userHonor->comment)
                                                                    ? $userHonor->honor->title . ' ('. $userHonor->comment .')'
                                                                    : $userHonor->honor->title,
                                                                'alt' => $userHonor->honor->title])
                                                            }}
                                                        </a>
                                                    @else
                                                        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}#honors" title="Посмотреть все награды">...</a>
                                                        <?php break; ?>
                                                    @endif
                                                @endforeach
                                            </div>
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
            </div>
        @else
            <p>Пользователей не найдено.</p>
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