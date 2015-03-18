@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li>Мои комментарии</li>
            </ol>
        </div>

        <div class="col-lg-3">

        </div>
        <div class="col-lg-9">
            <h2>Мои комментарии</h2>

            <div id="comments">

                @foreach($user->comments as $comment)

                    <div data-comment-id="{{ $comment->id }}" class="col-md-12">
                        <div class="well">
                            <div class="date date-create">{{ $comment->created_at }}</div>
                            {{ $comment->comment }}
                            <div class="status">
                                Статус:
                                {{ ($comment->is_published) ? 'Опубликован' : 'Ожидает модерации' }}
                            </div>
                            <div class="on-page">
                                На странице:
                                @if(($comment->is_published))
                                    <a href="{{ URL::to($comment->getUrl()) }}">{{ $comment->page->getTitle() }}</a>
                                @else
                                    <a href="{{ URL::to($comment->page->getUrl()) }}">{{ $comment->page->getTitle() }}</a>
                                @endif
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        </div>
    </div>
@stop