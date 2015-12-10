<?php $data = isset($data) ? $data : (Request::all() ? Request::all() : []); ?>
@foreach($questions as $key => $question)
    @if(0 != $key)
        <hr>
    @endif
    @include('site.questionInfo', ['pageId' => $pageId])
@endforeach
{{ $questions->appends($data)->links() }}