@extends('layouts.main')

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li class="home-page">
            <a href="{{ URL::to('/') }}">
                <i class="material-icons">home</i>
            </a>
        </li>
        <li>{{ $page->getTitleForBreadcrumbs() }}</li>
    </ol>
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
                    <a class="fancybox" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage('origin', ['class' => 'page-image']) }}
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