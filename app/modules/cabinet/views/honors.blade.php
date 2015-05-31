@extends('cabinet::layouts.honors')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li>Награды</li>
    </ol>

    <section id="content" class="well">

        @if($page->title)
            <h2>{{ $page->title }}</h2>
        @endif

        @if($page->content)
            <div class="content">

                @if($page->showViews())
                    Количество просмотров: {{ $page->views }}
                @endif

                @if($page->showRating())
                    {{-- Рейтинг --}}
                    @include('widgets.rating')
                @endif

                {{ $page->content }}
            </div>
        @endif

        @if($page->showComments())
            {{-- Комментарии --}}
            <?php $commentWidget = app('CommentWidget') ?>
            {{ $commentWidget->show($page) }}
        @endif

    </section>
@stop
