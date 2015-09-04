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
                        {{ $page->getImage('origin') }}
                    </a>
                @endif
                {{ $page->getContentWithWidget() }}
            </div>
        @endif

        {{ $areaWidget->contentMiddle() }}

        @if(count($tagsByAlphabet))

            <section id="letters">
                @foreach($tagsByAlphabet as $letter => $tags)
                    <a href="#{{ $letter }}" class="btn btn-default btn-sm">
                        {{ $letter }}
                    </a>
                @endforeach
            </section>

            <section id="tags-area" class="blog margin-top-10">
                <div class="count">
                    Всего тегов: <span>{{ count($tagsByAlphabet) }}</span>.
                </div>
                @foreach($tagsByAlphabet as $letter => $tags)
                    <div class="letter-section">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="{{ $letter }}" class="letter-title">
                                    <span class="letter">{{ $letter }}</span>
                                    <span class="count pull-right">количество тегов: {{ count($tags) }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="tags">
                                    <div class="row">
                                        @foreach($tags as $tag)
                                            <div class="col-md-4">
                                                <a href="{{ URL::route('journal.tag', ['journalAlias' => $journalAlias, 'tag' => $tag->title]) }}">
                                                    @if($tag->image)
                                                        {{ $tag->getImage(null, ['width' => '20px', 'class' => 'pull-left']) }}
                                                    @endif
                                                    <span class="pull-left">
                                                        {{ $tag->title }}
                                                        ({{ count($tag->pages) }})
                                                    </span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section><!--blog-area-->
        @endif

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
