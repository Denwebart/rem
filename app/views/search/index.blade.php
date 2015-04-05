@extends('layouts.search')

<?php
$title = 'Результаты поиска';
View::share('title', $title);
?>

@section('content')
<section id="content">
	<h2>Результаты поиска по фразе "{{{ $query }}}"</h2>

	<div class="content">

		@if(count($results))
			@foreach($results as $result)

				<div>
					<a href="{{ URL::to($result->getUrl()) }}">
						{{ StringHelper::getFragment($result->getTitle(), $query) }}
					</a>
					<p>
						{{ StringHelper::getFragment($result->content, $query) }}
					</p>
				</div>
				<hr/>

			@endforeach

			{{ $results->appends(['query' => $query])->links() }}
		@else
			<p>Ничего не найдено.</p>
		@endif

	</div>
</section>
@stop