<div id="tags-sidebar-widget" class="sidebar-widget">
    @foreach($tags as $tag)
        <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" class="tag btn btn-primary btn-sm">
            {{ $tag->title }}
            <span class="label">
                {{ count($tag->pages) }}
            </span>
        </a>
    @endforeach
    <div class="all-tags">
        <a href="{{ URL::route('journal.tags', ['journalAlias' => Config::get('settings.journalAlias')]) }}">Все теги</a>
    </div>
</div>