@extends('layouts.main')

@section('content')
	<section id="content">
		<h2>{{ $page->title }}</h2>
		<div class="content">
			{{ $page->content }}
		</div>
	</section>
@stop