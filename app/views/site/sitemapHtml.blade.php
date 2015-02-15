@extends('layouts.main')

@section('content')
<section id="content">
	@if($page->title)
		<h2>{{ $page->title }}</h2>
	@endif
	@if($page->content)
		<div class="content">
			{{ $page->content }}
		</div>
	@endif
	<section id="sitemap-area">
		<ul id="sitemap">
			@foreach($pages as $page)
				<li>
					<a href="{{ URL::to($page->alias) }}">{{ $page->getTitle() }}</a>
					@if(count($page->publishedChildren))
						<ul>
							@foreach($page->publishedChildren as $secondLevel)
								<li>
									<a href="{{ URL::to($page->alias . '/' . $secondLevel->alias) }}">
										{{ $secondLevel->getTitle() }}
									</a>
									@if(count($secondLevel->publishedChildren))
										<ul>
											@foreach($secondLevel->publishedChildren as $thirdLevel)
												<li>
													<a href="{{ URL::to($page->alias . '/' . $secondLevel->alias . '/' . $thirdLevel->alias) }}">
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
</section>
@stop