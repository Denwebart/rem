@extends('layouts.search')

<?php
$title = 'Результаты поиска';
View::share('title', $title);
?>

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li>Поиск</li>
    </ol>

    <section id="content">
        @if($tag)
            <h2>Результаты поиска по тегу "{{{ $tag }}}"</h2>
        @else
            <h2>Результаты поиска по фразе "{{{ $query }}}"</h2>
        @endif

        <div class="content">

            {{--<div>--}}
                {{--<h4>Сортировка</h4>--}}
                {{--<a href="">по дате</a>--}}
                {{--<a href="">по алфавиту</a>--}}
            {{--</div>--}}
            {{--<hr/>--}}

            @if(count($results))
                <section id="search-area" class="blog">
                    <div class="count">
                        Показано результатов: <span>{{ $results->count() }}</span>.
                        Всего: <span>{{ $results->getTotal() }}</span>.
                    </div>
                    @foreach($results as $result)
                        <div>
                            <a href="{{ URL::to($result->getUrl()) }}">
                                {{ StringHelper::getFragment($result->getTitle(), $query) }}
                            </a>
                            <p>
                                {{ StringHelper::getFragment($result->content, $query) }}
                            </p>
                        </div>
                        <hr/>
                    @endforeach
                    {{ $results->appends(['query' => $query])->links() }}
                </section><!--search-area-->
            @else
                <p>Ничего не найдено.</p>
            @endif

        </div>

        {{ $areaWidget->contentBottom() }}

    </section>
@stop