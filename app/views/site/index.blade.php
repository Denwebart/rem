@extends('layouts.main')

@section('content')
	<section id="content" class="well">

        @if(!Request::has('stranitsa') || Request::get('stranitsa') == 1)
            <div class="row">
                <div class="@if($page->showRating()) col-lg-9 col-md-12 col-sm-9 col-xs-12 @else col-lg-12 col-md-12 col-sm-12 col-xs-12 @endif">
                    @if($page->title)
                        <h2>{{ $page->title }}</h2>
                    @endif
                </div>
                @if($page->showRating())
                    <div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
                        {{-- Рейтинг --}}
                        @include('widgets.rating')
                    </div>
                @endif
            </div>

            <div class="clearfix"></div>

            {{ $areaWidget->contentTop() }}

            @if($page->content)
                <div class="content">
                    @if($page->image)
                        <a class="fancybox pull-left" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                            {{ $page->getImage('origin', ['class' => 'page-image']) }}
                        </a>
                    @endif
                    {{ $page->getContentWithWidget() }}

                    <div class="clearfix"></div>
                    @include('widgets.sidebar.socialButtons')
                </div>
            @endif

            {{ $areaWidget->contentMiddle() }}
        @endif

		<section id="blog-area" class="blog margin-top-10">
            <div class="count">
                Показано: <span>{{ $articles->count() }}</span>.
                Всего: <span>{{ $articles->getTotal() }}</span>.
            </div>
			@foreach($articles as $key => $article)
                @if(0 != $key)
                    <hr/>
                @endif
				@include('site.postInfo')
			@endforeach
			{{ $articles->links() }}
		</section><!--blog-area-->

		{{ $areaWidget->contentBottom() }}
	</section>
@stop