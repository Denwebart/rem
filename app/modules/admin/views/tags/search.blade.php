@foreach($tags as $tag)
    <a href="javascript:void(0)" class="btn btn-labeled btn-info add-to-input">
        <span class="btn-label">
            {{ count($tag->pagesTags) }}
        </span>
        <span class="text">
            {{ $tag->title }}
        </span>
    </a>
@endforeach