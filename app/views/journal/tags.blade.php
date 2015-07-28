@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li><a href="{{ URL::to($page->parent->getUrl()) }}">{{ $page->parent->getTitle() }}</a></li>
        <li>{{ $page->getTitle() }}</li>
    </ol>

    <section id="content" class="well">

        @if($page->title)
            <h2>{{ $page->title }}</h2>
        @endif

        {{ $areaWidget->contentTop() }}

        @if($page->content)
            <div class="content">
                @if($page->image)
                    <a class="fancybox" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage() }}
                    </a>
                @endif
                {{ $page->getContentWithWidget() }}
            </div>
        @endif

        {{ $areaWidget->contentMiddle() }}

        @if(count($tagsByAlphabet))

            <section id="tags-area">
                @foreach($tagsByAlphabet as $letter => $tags)
                    <a href="#{{ $letter }}" class="btn btn-info btn-sm">
                        {{ $letter }}
                    </a>
                @endforeach
            </section>

            <section id="tags-area" class="blog">
                <div class="count">
                    Всего тегов: <span>{{ count($tagsByAlphabet) }}</span>.
                </div>
                @foreach($tagsByAlphabet as $letter => $tags)
                    <div class="row">
                        <div class="col-md-12">
                            <div id="{{ $letter }}" class="letter" style="background: #ccc; height: 30px">
                                {{ $letter }}
                                <span class="count pull-right">количество тегов: {{ count($tags) }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <ul>
                                @foreach($tags as $tag)
                                    <li>
                                        <a href="{{ URL::route('journal.tag', ['journalAlias' => $journalAlias, 'tag' => $tag->title]) }}">
                                            {{ $tag->getImage(null, ['width' => '20px']) }}
                                            {{ $tag->title }}
                                            ({{ count($tag->pages) }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </section><!--blog-area-->
        @endif

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
