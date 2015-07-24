@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои ответы' : 'Ответы пользователя ' . $user->login) : 'Ответы пользователя ' . $user->login;
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
                    {{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login }}
                </a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">
                <h2>{{ $title }}</h2>

                @if(Auth::check())
                    @if(Auth::user()->is($user))
                        @if($user->is_banned)
                            @include('cabinet::user.banMessage')
                        @elseif($headerWidget->isBannedIp)
                            @include('messages.bannedIp')
                        @endif
                    @endif
                @endif

                @if(count($answers))
                    <section id="answers-area" class="blog">
                        <div class="count">
                            Показано ответов: <span>{{ $answers->count() }}</span>.
                            Всего: <span>{{ $answers->getTotal() }}</span>.
                        </div>

                        @foreach($answers as $answer)
                            <div data-comment-id="{{ $answer->id }}" class="well">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>
                                            @if($answer->page)
                                                <a href="{{ URL::to($answer->getUrl()) }}">
                                                    {{ $answer->page->title }}
                                                </a>
                                            @else
                                                вопрос удален
                                            @endif
                                            <div class="pull-right">
                                                @if(Auth::check())
                                                    @if((Auth::user()->is($answer->user) && !$headerWidget->isBannedIp && !Auth::user()->is_banned) || Auth::user()->isAdmin())
                                                        <div class="buttons pull-left">
                                                            {{--<a href="{{ URL::route('user.journal.edit', ['login' => $user->getLoginForUrl(),'id' => $answer->id]) }}" class="btn btn-info btn-sm" title="Редактировать статью">--}}
                                                            {{--<span class="mdi-editor-mode-edit"></span>--}}
                                                            {{--</a>--}}
                                                            {{--<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-question" data-id="{{ $answer->id }}" title="Удалить статью">--}}
                                                            {{--<span class="mdi-content-clear"></span>--}}
                                                            {{--</a>--}}
                                                            <div class="status">
                                                                Статус:
                                                                {{ ($answer->is_published) ? 'Опубликован' : 'Ожидает модерации' }}
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
                                            {{ DateHelper::dateFormat($answer->created_at) }}
                                        </div>
                                        <div class="pull-right">
                                            @if(Comment::MARK_BEST == $answer->mark)
                                                <div class="best pull-left">
                                                    <i class="mdi-action-done mdi-success"></i>
                                                </div>
                                            @endif
                                            <div class="rating pull-left" title="Оценка комментария">
                                                <span class="mdi-action-thumbs-up-down"></span>
                                                {{ $answer->votes_like - $answer->votes_dislike }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        {{ $answer->comment }}
                                    </div>

                                    <div class="col-md-12">
                                        <div class="comments-for-answer">
                                            Комментарии к ответу:
                                            <a href="{{ URL::to($answer->getUrl()) }}">
                                                {{ count($answer->publishedChildren) }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{ $answers->links() }}
                    </section>
                @else
                    @if(Auth::user()->is($user))
                        <p>
                            Вы еще не ответили ни на один вопрос.
                        </p>
                    @else
                        <p>
                            Ответов нет.
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