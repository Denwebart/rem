@extends('layouts.top')

<?php
$title = 'Топ';
View::share('title', $title);
?>

@section('content')
<section id="content">

    <h2>Топ</h2>

	<div class="content">

		{{--<div>--}}
			{{--<h4>Сортировка</h4>--}}
			{{--<a href="">по дате</a>--}}
			{{--<a href="">по алфавиту</a>--}}
		{{--</div>--}}
		{{--<hr/>--}}

		@if(count($pages))
			@foreach($pages as $key => $item)
				<div class="item">
                    {{ $key + 1 }}.
					<a href="{{ URL::to($item->getUrl()) }}">
						{{ $item->getTitle() }}
					</a>
				</div>
				<hr/>
			@endforeach

{{--			{{ $pages->appends(['query' => $query])->links() }}--}}
			{{ $pages->links() }}
		@else
			<p>Ничего не найдено.</p>
		@endif

	</div>
</section>
@stop