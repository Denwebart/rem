@extends('layouts.main')

@section('content')
    <section id="content">

        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
            @if($page->parent)
                <li>
                    <a href="{{ URL::to($page->parent->alias) }}">
                        {{ $page->parent->getTitle() }}
                    </a>
                </li>
            @endif
            <li>{{ $page->getTitle() }}</li>
        </ol>

        @if($page->title)
            <h2>{{ $page->title }}</h2>
        @endif
        @if($page->content)
            <div class="content">
                {{ $page->content }}
            </div>
        @endif

        @if(Auth::check())
            <div class="row">
                <div class="col-md-12">
                    <a href="" class="btn btn-success pull-right">Ответить</a>
                </div>
            </div>
        @endif



    </section>
@stop
