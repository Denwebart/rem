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

		@if($page->is_show_title)
			<h2>{{ $page->title }}</h2>
		@endif

		{{ $areaWidget->contentTop() }}

		@if($page->content)
			<div class="content">
                @if($page->image)
                    <a class="fancybox pull-left" rel="group-content" href="{{ $page->getImageLink('origin') }}">
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
						<a href="{{ URL::to($item->getUrl()) }}">
                            @if($item->menuItem)
                                {{ $item->menuItem->menu_title }}
                            @else
                                {{ $item->getTitle() }}
                            @endif
                        </a>
						@if(count($item->publishedChildren))
							<ul>
								@foreach($item->publishedChildren as $secondLevel)
									<li>
										<a href="{{ URL::to($secondLevel->getUrl()) }}">
                                            @if($secondLevel->menuItem)
                                                {{ $secondLevel->menuItem->menu_title }}
                                            @else
                                                {{ $secondLevel->getTitle() }}
                                            @endif
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