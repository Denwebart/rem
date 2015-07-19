@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои вопросы' : 'Вопросы пользователя ' . $user->login) : 'Вопросы пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        {{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login}}
                    </a>
                </li>
                <li>{{ $title }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ $user->getAvatar() }}
            </div>

            {{ $areaWidget->leftSidebar() }}
        </div>
        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-12">
                    <h2>{{ $title }}</h2>

                    @if(Auth::check())
                        @if(Auth::user()->is($user))
                            @if(!$headerWidget->isBannedIp)
                                @if(!$user->is_banned)
                                    <a href="{{ URL::route('user.questions.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                                        Задать вопрос
                                    </a>
                                @else
                                    @include('cabinet::user.banMessage')
                                @endif
                            @else
                                @include('messages.bannedIp')
                            @endif
                        @endif
                    @endif

                    <div id="questions">

                        @if(count($questions))
                            @foreach($questions as $question)

                                <div data-question-id="{{ $question->id }}" class="col-md-12">
                                    <div class="well">
                                        @if(Auth::check())
                                            @if((Auth::user()->is($user) && !$headerWidget->isBannedIp && !$user->is_banned) || Auth::user()->isAdmin())
                                                <div class="pull-right">
                                                    <a href="{{ URL::route('user.questions.edit', ['login' => $user->getLoginForUrl(),'id' => $question->id]) }}" class="btn btn-info">
                                                        Редактировать
                                                    </a>
                                                    <a href="javascript:void(0)" class="btn btn-danger delete-question" data-id="{{ $question->id }}">
                                                        Удалить
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                        <h3>
                                            <a href="{{ URL::to($question->getUrl()) }}">
                                                {{ $question->title }}
                                            </a>
                                        </h3>
                                        <div class="date date-create">{{ $question->created_at }}</div>

                                        <div>
                                            @if($question->image)
                                                {{ $question->getImage(null, ['width' => '100px']) }}
                                            @endif
                                            {{ $question->getIntrotext() }}
                                        </div>

                                        <div class="answers">
                                            Ответы:
                                            @if(count($question->bestComments))
                                                <i class="mdi-action-done mdi-success" style="font-size: 20pt;"></i>
                                            @endif
                                            <a href="{{ URL::to($question->getUrl()) }}#answers">
                                                {{ count($question->publishedComments) }}
                                            </a>
                                        </div>

                                        {{--<div class="status">--}}
                                        {{--Статус:--}}
                                        {{--{{ ($question->is_published) ? 'Опубликован' : 'Ожидает модерации' }}--}}
                                        {{--</div>--}}

                                    </div>
                                </div>

                            @endforeach

                            <div>
                                {{ $questions->links() }}
                            </div>
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
                </div>
                <div class="col-lg-12">
                    {{ $areaWidget->contentBottom() }}
                </div>
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