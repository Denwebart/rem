<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
    <li class="home-page" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a href="{{ URL::to('/') }}" itemprop="item">
            <i class="material-icons">home</i>
            <meta itemprop="name" content="Главная" />
        </a>
        <meta itemprop="position" content="1" />
    </li>
    @foreach(array_values($items) as $key => $item)
        @if(isset($item['url']))
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ $item['url'] }}" itemprop="item">
                    <span itemprop="name">{{ $item['title'] }}</span>
                </a>
                <meta itemprop="position" content="{{ $key + 2 }}" />
            </li>
        @else
            <li class="hidden-md hidden-xs" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ $page->getUrl() }}" itemprop="item" class="hidden">
                    {{ $item['title'] }}
                </a>
                <span itemprop="name">{{ $item['title'] }}</span>
                <meta itemprop="position" content="{{ $key + 2 }}" />
            </li>
        @endif
    @endforeach
</ol>