@extends('layouts.search')

<?php
$title = 'Результаты поиска';
View::share('title', $title);
?>

@section('content')
<section id="content">
	<h2>Результаты поиска по фразе "{{ $search }}"</h2>

	<div class="content">

		@foreach($results as $result)

			<div>
				{{ $result->getTitle() }}
			</div>
			<hr/>

		@endforeach

		{{ $results->links() }}

	</div>
</section>
@stop