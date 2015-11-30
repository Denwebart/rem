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

		@if($page->is_show_title)
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