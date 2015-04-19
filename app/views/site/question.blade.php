@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        @if($page->parent)
            <li>
                <a href="{{ URL::to($page->parent->parent->getUrl()) }}">
                    {{ $page->parent->parent->getTitle() }}
                </a>
            </li>
            <li>
                <a href="{{ URL::to($page->parent->getUrl()) }}">
                    {{ $page->parent->getTitle() }}
                </a>
            </li>
        @endif
        <li>{{ $page->getTitle() }}</li>
    </ol>

    <section id="content" class="well">

        @if($page->title)
            <h2>{{ $page->title }}</h2>
        @endif
        @if($page->content)
            <div class="content">
                {{ $page->content }}
            </div>
        @endif

        @if(Auth::check())
            {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                    {{--<a href="" class="btn btn-success pull-right">Ответить</a>--}}
                {{--</div>--}}
            {{--</div>--}}
        @endif

        <div id="answers">
            {{-- Комментарии --}}
            <?php
                $commentWidget = app('CommentWidget');
                $commentWidget->title = 'Ответы';
                $commentWidget->formTitle = 'Написать ответ';
                $commentWidget->successMessage = 'Спасибо за ответ!';
            ?>
            {{ $commentWidget->show($page) }}
        </div>

    </section>
@stop
