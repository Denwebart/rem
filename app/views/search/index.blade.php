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

    <section id="content" class="well">
        @if($tag)
            <h2>Результаты поиска по тегу "{{{ $tag }}}"</h2>
        @else
            <h2>
                Результаты поиска
                @if($query)
                    по фразе "{{{ $query }}}"
                @endif
            </h2>
        @endif

        <div class="content">

            {{--<div>--}}
                {{--<h4>Сортировка</h4>--}}
                {{--<a href="">по дате</a>--}}
                {{--<a href="">по алфавиту</a>--}}
            {{--</div>--}}
            {{--<hr/>--}}

            @if(count($results))
                <section id="search-area" class="blog margin-top-10">
                    <div class="count">
                        Показано результатов: <span>{{ $results->count() }}</span>.
                        Всего: <span>{{ $results->getTotal() }}</span>.
                    </div>

                    @foreach($results as $result)
                        <div class="row item" data-page-id="{{ $result->id }}">
                            <div class="col-md-12">
                                <h3>
                                    <a href="{{ URL::to($result->getUrl()) }}">
                                        {{ StringHelper::getFragment($result->getTitle(), $query) }}
                                    </a>
                                </h3>
                            </div>
                            <div class="col-md-12">
                                <p>{{ StringHelper::getFragment($result->content, $query) }}</p>
                            </div>
                        </div>
                        <hr>
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