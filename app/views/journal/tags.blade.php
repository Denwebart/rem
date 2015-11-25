@extends('layouts.main')

@section('breadcrumbs')
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => [
        [
            'title' => $parent->getTitle(),
            'url' => URL::to($parent->getUrl())
        ],
        [
            'title' => $page->getTitle()
        ]
    ]])
@stop

@section('content')
    <section id="content" class="well">

        @if($page->title)
            <h2>{{ $page->title }}</h2>
        @endif

        {{ $areaWidget->contentTop() }}

        @if($page->content)
            <div class="content">
                @if($page->image)
                    <a class="fancybox pull-left" data-fancybox-group="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage('origin', ['class' => 'page-image']) }}
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
                                            <div class="col-md-4 col-sm-4 col-xs-6">
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
        @else
            Тегов нет.
        @endif

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
