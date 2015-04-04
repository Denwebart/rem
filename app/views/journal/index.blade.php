@extends('layouts.main')

@section('content')
    <section id="content">

        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
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

        @if(count($articles))
            <section id="blog-area">
                @foreach($articles as $article)
                    <div class="row">
                        <div class="col-md-12">
                            <h3>
                                <a href="{{ URL::to($article->getUrl()) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                        </div>
                        <div class="col-md-5">
                            <a href="{{ URL::to($article->getUrl()) }}">
                                {{ HTML::image(Config::get('settings.defaultImage'), '', ['class' => 'img-responsive']) }}
                            </a>
                        </div>
                        <div class="col-md-7">
                            <p>{{ $article->getIntrotext() }}</p>
                            <a class="pull-right" href="#">Читать полностью <span class="glyphicon glyphicon-chevron-right"></span></a>
                        </div>
                    </div>
                    <hr/>
                @endforeach
            </section><!--blog-area-->
        @endif

    </section>
@stop
