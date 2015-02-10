@extends('layouts.main')

@section('content')
<section id="content">
	<h2>{{ $page->title }}</h2>
	<div class="content">
		{{ $page->content }}

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

	</div>
</section>
@stop