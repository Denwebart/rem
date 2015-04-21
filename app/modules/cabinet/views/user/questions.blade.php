@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Мои вопросы' : 'Вопросы пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                    </a>
                </li>
                <li>{{ $title }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ $user->getAvatar() }}
            </div>
        </div>
        <div class="col-lg-9">
            <h2>{{ $title }}</h2>

            @if(Auth::user()->is($user))
                <a href="{{ URL::route('user.questions.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                    Задать вопрос
                </a>
            @endif

            <div id="questions">

                @foreach($questions as $question)

                    <div data-question-id="{{ $question->id }}" class="col-md-12">
                        <div class="well">
                            @if(Auth::user()->is($user))
                                <div class="pull-right">
                                    <a href="{{ URL::route('user.questions.edit', ['login' => $user->getLoginForUrl(),'id' => $question->id]) }}" class="btn btn-info">
                                        Редактировать
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-danger delete-question" data-id="{{ $question->id }}">
                                        Удалить
                                    </a>
                                </div>
                            @endif
                            <h3>
                                <a href="{{ URL::to($question->getUrl()) }}">
                                    {{ $question->title }}
                                </a>
                            </h3>
                            <div class="date date-create">{{ $question->created_at }}</div>

                            <div>
                                {{ $question->getIntrotext() }}
                            </div>

                            <div class="status">
                                Статус:
                                {{ ($question->is_published) ? 'Опубликован' : 'Ожидает модерации' }}
                            </div>

                        </div>
                    </div>

                @endforeach

                <div>
                    {{ $questions->links() }}
                </div>

            </div>

        </div>
    </div>
@stop

@section('script')
    @parent

    <!-- Delete Question -->
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
@stop