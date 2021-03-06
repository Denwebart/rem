<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<?php echo '<?xml-stylesheet type="text/xsl" href="sitemap.xsl"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($pages as $page)
        <url>
            <loc>{{ URL::to($page->getUrl()) }}</loc>
            <lastmod>{{ DateHelper::dateFormatForSchema($page->updated_at, false) }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
        @if(count($page->publishedChildren))
            @foreach($page->publishedChildren as $secondLevel)
                <url>
                    <loc>{{ URL::to($secondLevel->getUrl()) }}</loc>
                    <lastmod>{{ DateHelper::dateFormatForSchema($secondLevel->updated_at, false) }}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                </url>
                @if(count($secondLevel->publishedChildren))
                    @foreach($secondLevel->publishedChildren as $thirdLevel)
                        <url>
                            <loc>{{ URL::to($thirdLevel->getUrl()) }}</loc>
                            <lastmod>{{ DateHelper::dateFormatForSchema($thirdLevel->updated_at, false) }}</lastmod>
                            <changefreq>daily</changefreq>
                            <priority>1.0</priority>
                        </url>
                    @endforeach
                @endif
            @endforeach
        @endif
    @endforeach
</urlset>