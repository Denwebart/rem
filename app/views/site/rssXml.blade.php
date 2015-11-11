{{ '<?xml version="1.0" encoding="UTF-8" ?>' }}
{{ '<?xml-stylesheet type="text/xsl" href="rss.xsl" ?>' }}
{{ '<?xml-stylesheet type="text/css" href="/css/rss.css" ?>' }}
<rss version="2.0">
    <channel>
        <title>{{ isset($settings) ? $settings['siteTitle']['value'] : '' }}</title>
        <description>{{ isset($settings) ? $settings['siteSlogan']['value'] : '' }}</description>
        <link>{{ URL::to(Config::get('app.url')) }}</link>
        <copyright><![CDATA[{{ isset($settings) ? $settings['copyright']['value'] : '' }}]]> 2010 - 2015</copyright>
        <image>
            <url>{{ URL::to('/images/logo.png') }}</url>
            <title>{{ isset($settings) ? $settings['siteTitle']['value'] : '' }}</title>
            <link>{{ URL::to(Config::get('app.url')) }}</link>
        </image>
        <lastBuildDate>{{ \Carbon\Carbon::now() }}</lastBuildDate>
        @foreach($pages as $page)
            <item>
                <title>{{ $page->title }}</title>
                <description>
                    <![CDATA[
                        <div class="author info-item">
                            <span>Автор:</span>
                            <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                                {{ $page->user->getAvatar('mini', ['width' => '25px']) }}
                                <span>{{ $page->user->login }}</span>
                            </a>
                        </div>
                        <div class="category info-item">
                            Категория:
                            @if($page->parent->parent)
                                <a href="{{ URL::to($page->parent->parent->getUrl()) }}">
                                    @if($page->parent->parent->menuItem)
                                        {{ $page->parent->parent->menuItem->menu_title }}
                                    @else
                                        {{ $page->parent->parent->title }}
                                    @endif
                                </a>
                                /
                            @endif
                            <a href="{{ URL::to($page->parent->getUrl()) }}">
                                @if($page->parent->menuItem)
                                    {{ $page->parent->menuItem->menu_title }}
                                @else
                                    {{ $page->parent->title }}
                                @endif
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        @if($page->image)
                            {{ $page->getImage(null, ['class' => 'image', 'style' => 'float: left;']) }}
                        @endif
                        {{ $page->getIntrotext() }}
                    ]]>
                </description>
                <link>{{ URL::to($page->getUrl()) }}</link>
                <pubDate>
                    {{ DateHelper::date('j/m/Y H:i:s', $page->published_at) }}
                </pubDate>
                <guid isPermaLink="false">{{ URL::to($page->getUrl()) }}</guid>
            </item>
        @endforeach
    </channel>
</rss>