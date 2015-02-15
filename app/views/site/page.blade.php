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
	</section>
@stop