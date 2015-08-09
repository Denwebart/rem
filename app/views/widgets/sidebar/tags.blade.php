<div id="tags-sidebar-widget" class="sidebar-widget">
    @foreach($tags as $tag)
        <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" title="{{ $tag->title }}" class="tag btn btn-sm btn-info">
            {{ $tag->title }} ({{ count($tag->pages) }})
        </a>
    @endforeach
    <div>
        <a href="{{ URL::route('journal.tags', ['journalAlias' => Config::get('settings.journalAlias')]) }}">Все теги</a>
    </div>
</div>