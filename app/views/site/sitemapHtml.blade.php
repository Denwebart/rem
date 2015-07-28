@extends('layouts.main')

@section('content')
	<ol class="breadcrumb">
		<li><a href="{{ URL::to('/') }}">Главная</a></li>
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

		<section id="sitemap-area">
			<ul id="sitemap">
				@foreach($pages as $item)
					<li>
						<a href="{{ URL::to($item->getUrl()) }}">{{ $item->getTitle() }}</a>
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
		</section><!--sitemap-area-->

		{{ $areaWidget->contentBottom() }}

	</section>
@stop