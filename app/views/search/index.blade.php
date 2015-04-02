@extends('layouts.search')

<?php
$title = 'Результаты поиска';
View::share('title', $title);
?>

@section('content')
<section id="content">
	<h2>Результаты поиска по фразе "{{ $search }}"</h2>

	<div class="content">

		@if(count($results))
			@foreach($results as $result)

				<div>
					<a href="{{ URL::to($result->getUrl()) }}">
						{{ StringHelper::getFragment($result->getTitle(), $search) }}
					</a>
					<p>{{ StringHelper::getFragment($result->content, $search) }}</p>
				</div>
				<hr/>

			@endforeach
		@else
			<p>Ничего не найдено.</p>
		@endif

		{{ $results->appends(array('search' => $search))->links() }}

	</div>
</section>
@stop