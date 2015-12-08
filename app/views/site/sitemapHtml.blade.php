@extends('layouts.main')

@section('breadcrumbs')
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => [
        [
            'title' => $page->getTitleForBreadcrumbs()
        ]
    ]])
@stop

@section('content')
	<section id="content" class="well">

        <div itemscope itemtype="http://schema.org/Article">

            <meta itemprop="datePublished" content="{{ DateHelper::dateFormatForSchema($page->published_at) }}">
            @if($page->is_show_title)
                <h2 itemprop="headline">{{ $page->title }}</h2>
            @else
                <meta itemprop="headline" content="{{ $page->getTitle() }}">
            @endif

            {{ $areaWidget->contentTop() }}

            @if($page->content)
                <div class="content" itemprop="articleBody">
                    @if($page->image)
                        <a class="fancybox pull-left" data-fancybox-group="group-content" href="{{ $page->getImageLink('origin') }}">
                            {{ $page->getImage('origin', ['class' => 'page-image']) }}
                        </a>
                    @else
                        <meta itemprop="image" content="{{ URL::to(Config::get('settings.defaultImage')) }}">
                    @endif
                    {{ $page->getContentWithWidget() }}
                </div>
            @else
                <meta itemprop="image" content="{{ URL::to(Config::get('settings.defaultImage')) }}">
            @endif

            {{ $areaWidget->contentMiddle() }}
        </div>

		<div id="sitemap-area">
			<ul id="sitemap">
				@foreach($pages as $item)
					<li>
						<a href="{{ URL::to($item->getUrl()) }}">
                            {{ $item->getTitle() }}
                        </a>
						@if(count($item->publishedChildren))
							<ul>
								@foreach($item->publishedChildren as $secondLevel)
									<li>
										<a href="{{ URL::to($secondLevel->getUrl()) }}">
                                            {{ $secondLevel->getTitle() }}
										</a>
										@if(count($secondLevel->publishedChildren))
											<ul>
												@foreach($secondLevel->publishedChildren as $thirdLevel)
													<li>
														<a href="{{ URL::to($thirdLevel->getUrl()) }}">
															{{ $thirdLevel->getTitle() }}
														</a>
													</li>
												@endforeach
											</ul>
										@endif
									</li>
								@endforeach
							</ul>
						@endif
					</li>
				@endforeach
			</ul>
		</div><!--sitemap-area-->

		{{ $areaWidget->contentBottom() }}

	</section>
@stop