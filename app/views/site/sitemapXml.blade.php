{{ '<?xml version="1.0" encoding="UTF-8"?>' }}
{{ '<?xml-stylesheet type="text/xsl" href="sitemap.xsl"?>' }}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($pages as $page)
        <url>
            <loc>{{ URL::to($page->alias) }}</loc>
            <lastmod>{{ $page->updated_at }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
        @if(count($page->children))
            @foreach($page->children as $secondLevel)
                <url>
                    <loc>{{ URL::to($page->alias . '/' . $secondLevel->alias) }}</loc>
                    <lastmod>{{ $secondLevel->updated_at }}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                </url>
                @if(count($secondLevel->children))
                    @foreach($secondLevel->children as $thirdLevel)
                        <url>
                            <loc>{{ URL::to($page->alias . '/' . $secondLevel->alias . '/' . $thirdLevel->alias) }}</loc>
                            <lastmod>{{ $thirdLevel->updated_at }}</lastmod>
                            <changefreq>daily</changefreq>
                            <priority>1.0</priority>
                        </url>
                    @endforeach
                @endif
            @endforeach
        @endif
    @endforeach
</urlset>