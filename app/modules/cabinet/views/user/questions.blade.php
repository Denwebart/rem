@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои вопросы' : 'Вопросы пользователя ' . $user->login) : 'Вопросы пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
            <li>
                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                    {{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login}}
                </a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-8">
                        <h2>{{ $title }}</h2>
                    </div>
                    <div class="col-md-4">
                        @if(Auth::check())
                            @if(Auth::user()->is($user))
                                @if(!$headerWidget->isBannedIp)
                                    @if(!$user->is_banned)
                                        <a href="{{ URL::route('user.questions.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                                            Задать вопрос
                                        </a>
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
                            <div data-question-id="{{ $question->id }}" class="well">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>
                                            @if(count($question->bestComments))
                                                <i class="mdi-action-done mdi-success" style="font-size: 20pt;"></i>
                                            @endif
                                            <a href="{{ URL::to($question->getUrl()) }}">
                                                {{ $question->title }}
                                            </a>
                                            <div class="pull-right">
                                                @if(Auth::check())
                                                    @if((Auth::user()->is($question->user) && !$headerWidget->isBannedIp && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                                                        <div class="buttons pull-left">
                                                            <a href="{{ URL::route('user.questions.edit', ['login' => $user->getLoginForUrl(),'id' => $question->id]) }}" class="btn btn-info btn-sm" title="Редактировать статью">
                                                                <span class="mdi-editor-mode-edit"></span>
                                                            </a>
                                                            <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-question" data-id="{{ $question->id }}" title="Удалить статью">
                                                                <span class="mdi-content-clear"></span>
                                                            </a>
                                                            <div class="status">
                                                                Статус:
                                                                {{ ($question->is_published) ? 'Опубликован' : 'Неопубликован' }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="date pull-left" title="Дата публикации">
                                            <span class="mdi-action-today"></span>
                                            {{ DateHelper::dateFormat($question->published_at) }}
                                        </div>
                                        <div class="pull-right">
                                            <div class="views pull-left" title="Количество просмотров">
                                                <span class="mdi-action-visibility"></span>
                                                {{ $question->views }}
                                            </div>
                                            <div class="saved pull-left" title="Сколько пользователей сохранили">
                                                <span class="mdi-content-archive"></span>
                                                {{ count($question->whoSaved) }}
                                            </div>
                                            <div class="rating pull-left" title="Рейтинг (количество проголосовавших)">
                                                <span class="mdi-action-grade"></span>
                                                {{ $question->getRating() }} ({{ $question->voters }})
                                            </div>
                                            <div class="rating pull-left" title="Количество подписавшихся на вопрос">
                                                <span class="mdi-maps-local-library"></span>
                                                {{ count($question->subscribers) }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <a href="{{ URL::to($question->getUrl()) }}" class="image">
                                            {{ $question->getImage(null, ['width' => '200px']) }}
                                        </a>
                                        <p>{{ $question->getIntrotext() }}</p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="answers">
                                            Ответы:
                                            @if(count($question->bestComments))
                                                <i class="mdi-action-done mdi-success" style="font-size: 20pt;"></i>
                                            @endif
                                            <a href="{{ URL::to($question->getUrl()) }}#answers">
                                                {{ count($question->publishedAnswers) }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{ $questions->links() }}
                    </section>
                @else
                    @if(Auth::user()->is($user))
                        <p>
                            Вы еще не задали ни одного вопроса.
                        </p>
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
                                if(response.success){
                                    $('[data-question-id=' + questionId + ']').remove();
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endif
@stop