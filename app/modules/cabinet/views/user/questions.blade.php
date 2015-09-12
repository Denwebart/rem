@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои вопросы' : 'Вопросы пользователя ' . $user->login) : 'Вопросы пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li class="home-page">
                <a href="{{ URL::to('/') }}">
                    <i class="material-icons">home</i>
                </a>
            </li>
            <li>
                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                    {{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login}}
                </a>
            </li>
            <li class="hidden-md hidden-xs">{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <div class="row">
                    <div class="col-lg-8 col-md-7 col-sm-8 col-xs-12">
                        <h2>{{ $title }}</h2>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-5 col-lg-4">
                        @if(Auth::check())
                            @if(Auth::user()->is($user))
                                @if(!$headerWidget->isBannedIp)
                                    @if(!$user->is_banned)
                                        <div class="button-group-full">
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{ URL::route('admin.questions.create') }}" class="btn btn-success btn-sm btn-full pull-right">
                                                    Задать вопрос
                                                </a>
                                            @else
                                                <a href="{{ URL::route('user.questions.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success btn-sm btn-full pull-right">
                                                    Задать вопрос
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @endif
                    </div>
                    @if(Auth::check())
                        @if(Auth::user()->is($user))
                            @if($headerWidget->isBannedIp)
                                <div class="col-md-12">
                                    @include('messages.bannedIp')
                                </div>
                            @endif
                            @if($user->is_banned)
                                <div class="col-md-12">
                                    @include('cabinet::user.banMessage')
                                </div>
                            @endif
                        @endif
                    @endif
                </div>

                @if(count($questions))
                    <section id="questions-area" class="blog">
                        <div class="count">
                            Показано вопросов: <span>{{ $questions->count() }}</span>.
                            Всего: <span>{{ $questions->getTotal() }}</span>.
                        </div>
                        @foreach($questions as $question)
                            <div class="well item" data-question-id="{{ $question->id }}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="date pull-left hidden-lg hidden-md hidden-sm" title="Дата публикации" data-toggle="tooltip">
                                                    <i class="material-icons pull-left">today</i>
                                                    <span class="pull-left">{{ DateHelper::dateFormat($question->published_at) }}</span>
                                                </div>
                                                <div class="page-info">
                                                    <div class="date pull-left hidden-xs" title="Дата публикации" data-toggle="tooltip">
                                                        <i class="material-icons">today</i>
                                                        <span>{{ DateHelper::dateFormat($question->published_at) }}</span>
                                                    </div>
                                                    <div class="pull-right">
                                                        <div class="views pull-left" title="Количество просмотров" data-toggle="tooltip">
                                                            <i class="material-icons">visibility</i>
                                                            <span>{{ $question->views }}</span>
                                                        </div>
                                                        <div class="saved-count pull-left" title="Сколько пользователей сохранили" data-toggle="tooltip">
                                                            <i class="material-icons">archive</i>
                                                            <span>{{ count($question->whoSaved) }}</span>
                                                        </div>
                                                        <div class="rating pull-left" title="Рейтинг (количество проголосовавших)" data-toggle="tooltip">
                                                            <i class="material-icons">grade</i>
                                                            <span>{{ $question->getRating() }} ({{ $question->voters }})</span>
                                                        </div>
                                                        <div class="subscribers pull-left" title="Количество подписавшихся на вопрос" data-toggle="tooltip">
                                                            <i class="material-icons">local_library</i>
                                                            <span>{{ count($question->subscribers) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-xs-9">
                                                <h3>
                                                    <a href="{{ URL::to($question->getUrl()) }}">
                                                        {{ $question->title }}
                                                    </a>
                                                </h3>
                                            </div>
                                            <div class="col-md-3 col-xs-3">
                                                <div class="answers-text">
                                                    <span>Ответов:</span>
                                                </div>
                                                <div class="answers-value">
                                                    <a href="{{ URL::to($question->getUrl()) }}#answers" class="count @if(count($question->bestComments)) best @endif">
                                                        {{ count($question->publishedAnswers) }}
                                                    </a>
                                                    @if(count($question->bestComments))
                                                        <a href="{{ URL::to($question->getUrl()) }}#answers">
                                                            <i class="material-icons mdi-success" title="Есть решение" data-toggle="tooltip">done</i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-xs-8">
                                                <div class="category">
                                                    <div class="text pull-left hidden-xs">
                                                        Категория:
                                                    </div>
                                                    <div class="link pull-left">
                                                        <a href="{{ URL::to($question->parent->getUrl()) }}">
                                                            {{ $question->parent->getTitle() }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-4">
                                                @if(Auth::check())
                                                    @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                                                        <div class="buttons pull-right">
                                                            @if(Auth::user()->isAdmin())
                                                                <a href="javascript:void(0)" class="pull-right delete-question" data-id="{{ $question->id }}" title="Удалить вопрос" data-toggle="tooltip" data-placement="top">
                                                                    <i class="material-icons">delete</i>
                                                                </a>
                                                            @endif
                                                            <a href="{{ URL::route('admin.questions.edit', ['id' => $question->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать вопрос" data-toggle="tooltip">
                                                                <i class="material-icons">mode_edit</i>
                                                            </a>
                                                        </div>
                                                    @elseif((Auth::user()->is($question->user) && !IP::isBanned() && !Auth::user()->is_banned && $question->isEditable()) || Auth::user()->isAdmin())
                                                        <div class="buttons pull-right">
                                                            <a href="javascript:void(0)" class="pull-right delete-question" data-id="{{ $question->id }}" title="Удалить вопрос" data-toggle="tooltip" data-placement="top">
                                                                <i class="material-icons">delete</i>
                                                            </a>
                                                            <a href="{{ URL::route('user.questions.edit', ['login' => $question->user->getLoginForUrl(),'id' => $question->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать вопрос" data-toggle="tooltip">
                                                                <i class="material-icons">mode_edit</i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{ $questions->links() }}
                    </section>
                @else
                    @if(Auth::check())
                        @if(Auth::user()->is($user))
                            <p>
                                Вы еще не задали ни одного вопроса.
                            </p>
                        @else
                            <p>
                                Вопросов нет.
                            </p>
                        @endif
                    @else
                        <p>
                            Вопросов нет.
                        </p>
                    @endif
                @endif
            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <!-- Delete Question -->
    @if(Auth::check())
        @if(Auth::user()->is($user) || Auth::user()->isAdmin())
            <script type="text/javascript">
                $('.delete-question').click(function(){
                    var questionId = $(this).data('id');
                    if(confirm('Вы уверены, что хотите удалить вопрос?')) {
                        $.ajax({
                            url: '<?php echo URL::route('user.questions.delete', ['login' => $user->getLoginForUrl()]) ?>',
                            dataType: "text json",
                            type: "POST",
                            data: {questionId: questionId},
                            beforeSend: function(request) {
                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                            },
                            success: function(response) {
                                if(response.success) {
                                    $('#site-messages').prepend(response.message);
                                    $('[data-question-id=' + questionId + ']').remove();
                                } else {
                                    $('#site-messages').prepend(response.message);
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endif
@stop