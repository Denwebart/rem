@extends('layouts.main')

@section('breadcrumbs')
    <?php
        if($page->parent_id != 0) {
            if($page->parent) {
                if($page->parent->parent_id != 0) {
                    if($page->parent->parent) {
                        $breadcrumbs[0]['title'] = $page->parent->parent->getTitle();
                        $breadcrumbs[0]['url'] = URL::to($page->parent->parent->getUrl());
                    }
                }
                $breadcrumbs[1]['title'] = $page->parent->getTitle();
                $breadcrumbs[1]['url'] = URL::to($page->parent->getUrl());
            }
        }
        $breadcrumbs[2]['title'] = $page->getTitleForBreadcrumbs();
    ?>
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => $breadcrumbs])
@stop

@section('content')
	<section id="content" class="well" itemscope itemtype="http://schema.org/Article">

        <meta itemprop="datePublished" content="{{ DateHelper::dateFormatForSchema($page->published_at) }}">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if($page->is_show_title)
                    <h2 itemprop="headline">{{ $page->title }}</h2>
                @endif
            </div>
        </div>

        {{ $areaWidget->contentTop() }}

		@if($page->content)
			<div class="content" itemprop="articleBody">
                @if($page->image)
                    <a class="fancybox pull-left" data-fancybox-group="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage('origin', ['class' => 'page-image']) }}
                    </a>
                @endif
				{{ $page->getContentWithWidget() }}
			</div>
		@endif

		{{ $areaWidget->contentMiddle() }}

        @if(count($children))
            <section id="blog-area" class="blog margin-top-10">
                <div class="count">
                    Показано: <span>{{ $children->count() }}</span>.
                    Всего: <span>{{ $children->getTotal() }}</span>.
                </div>
                @foreach($children as $key => $child)
                    @if(0 != $key)
                        <hr/>
                    @endif
                    @include('site.postInfo', ['article' => $child])
                @endforeach
                {{ $children->links() }}
            </section><!--blog-area-->
        @endif

		{{ $areaWidget->contentBottom() }}

	</section>
@stop
