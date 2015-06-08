@foreach($tags as $tag)
    <a href="javascript:void(0)" class="btn btn-labeled btn-default add-to-input" data-tag-id="{{ $tag->id }}">
        <span class="btn-label">
            {{ count($tag->pagesTags) }}
        </span>
        <span class="text">
            {{ $tag->title }}
        </span>
    </a>
@endforeach