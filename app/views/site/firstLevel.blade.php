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

		@if(count($page->publishedChildren))
			<section id="blog-area">
				@foreach($page->children as $child)

					<div class="row">
						<div class="col-md-12">
							<h3>
								<a href="{{ URL::to($page->alias . '/' . $child->alias) }}">
									{{ $child->title }}
								</a>
							</h3>
						</div>
						<div class="col-md-5">
							<a href="{{ URL::to($page->alias . '/' . $child->alias) }}">
								{{ HTML::image(Config::get('settings.defaultImage'), '', ['class' => 'img-responsive']) }}
							</a>
						</div>
						<div class="col-md-7">
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium veniam exercitationem expedita laborum at voluptate. Labore, voluptates totam at aut nemo deserunt rem magni pariatur quos perspiciatis atque eveniet unde.</p>
							<a class="pull-right" href="#">Читать полностью <span class="glyphicon glyphicon-chevron-right"></span></a>
						</div>
					</div>
					<hr/>

				@endforeach
			</section><!--blog-area-->
		@endif
	</section>
@stop