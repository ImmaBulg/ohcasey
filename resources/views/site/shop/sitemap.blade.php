{!! chr(60).'?xml version="1.0" encoding="utf-8"?'.chr(62) !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	
@foreach($links as $link)
    <url>
        <loc>{{ $link["loc"] }}</loc>
        <changefreq>{{ $link["changefreq"] }}</changefreq>
        <priority>{{ $link["priority"] }}</priority>
    </url>

@endforeach
</urlset>
