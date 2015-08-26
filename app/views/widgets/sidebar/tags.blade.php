<div id="tags-sidebar-widget" class="sidebar-widget">
    @foreach($tags as $tag)
        <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" title="{{ $tag->title }}" class="tag btn btn-sm btn-default">
            {{ $tag->title }} ({{ count($tag->pages) }})
        </a>
    @endforeach
    <div class="all-tags">
        <a href="{{ URL::route('journal.tags', ['journalAlias' => Config::get('settings.journalAlias')]) }}">Все теги</a>
    </div>
</div>