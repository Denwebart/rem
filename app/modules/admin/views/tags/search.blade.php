@if(count($tags))
    @foreach($tags as $tag)
        <div class="btn-group add-to-input" data-tag-id="{{ $tag->id }}">
            <a href="javascript:void(0)" class="btn btn-primary btn-outline tag-title">
                {{ $tag->title }}
            </a>
            <a href="javascript:void(0)" class="btn btn-primary tag-pages">
                {{ count($tag->pagesTags) }}
            </a>
        </div>
    @endforeach
@else
    <p>Ничего не найдено.</p>
@endif