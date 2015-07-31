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
            <div class="col-lg-12" id="content">
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
                                    <div class="col-md-10">
                                        @if(Auth::check())
                                            @if((Auth::user()->is($question->user) && !$headerWidget->isBannedIp && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                                                <div class="status pull-left">
                                                    @if($question->is_published)
                                                        <i class="material-icons mdi-success" title="Опубликован">lens</i>
                                                    @else
                                                        <i class="material-icons mdi-danger" title="Не опубликован">lens</i>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                        <h3>
                                            @if(count($question->bestComments))
                                                <i class="material-icons mdi-success">done</i>
                                            @endif
                                            <a href="{{ URL::to($question->getUrl()) }}">
                                                {{ $question->title }}
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="col-md-2">
                                        @if(Auth::check())
                                            @if((Auth::user()->is($question->user) && !$headerWidget->isBannedIp && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                                                <div class="buttons">
                                                    <a href="javascript:void(0)" class="pull-right delete-question" data-id="{{ $question->id }}" title="Удалить статью">
                                                        <i class="material-icons">delete</i>
                                                    </a>
                                                    <a href="{{ URL::route('user.questions.edit', ['login' => $user->getLoginForUrl(),'id' => $question->id]) }}" class="pull-right" title="Редактировать статью">
                                                        <i class="material-icons">mode-edit</i>
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <div class="page-info">
                                            <div class="date pull-left" title="Дата публикации">
                                                <i class="material-icons">today</i>
                                                <span>{{ DateHelper::dateFormat($question->published_at) }}</span>
                                            </div>
                                            <div class="pull-right">
                                                <div class="views pull-left" title="Количество просмотров">
                                                    <i class="material-icons">visibility</i>
                                                    <span>{{ $question->views }}</span>
                                                </div>
                                                <div class="saved-count pull-left" title="Сколько пользователей сохранили">
                                                    <i class="material-icons">archive</i>
                                                    <span>{{ count($question->whoSaved) }}</span>
                                                </div>
                                                <div class="rating pull-left" title="Рейтинг (количество проголосовавших)">
                                                    <i class="material-icons">grade</i>
                                                    <span>{{ $question->getRating() }} ({{ $question->voters }})</span>
                                                </div>
                                                <div class="subscribers pull-left" title="Количество подписавшихся на вопрос">
                                                    <i class="material-icons">local_library</i>
                                                    <span>{{ count($question->subscribers) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="category pull-right">
                                            <div class="text pull-left">
                                                Категория:
                                            </div>
                                            <div class="link pull-left">
                                                <a href="{{ URL::to($question->parent->getUrl()) }}">
                                                    {{ $question->parent->getTitle() }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        @if($question->image)
                                            <a href="{{ URL::to($question->getUrl()) }}" class="image">
                                                {{ $question->getImage(null, ['width' => '200px']) }}
                                            </a>
                                        @endif
                                        <p>{{ $question->getIntrotext() }}</p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="answers-text pull-left">
                                            <span>Ответов:</span>
                                        </div>
                                        <div class="answers-value pull-left">
                                            <a href="{{ URL::to($question->getUrl()) }}#answers" class="count @if(count($question->bestComments)) best @endif">
                                                {{ count($question->publishedAnswers) }}
                                            </a>
                                            @if(count($question->bestComments))
                                                <i class="material-icons mdi-success" title="Есть решение">done</i>
                                            @endif
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