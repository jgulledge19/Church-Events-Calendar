<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
    <title>[[*pagetitle]]</title>
    <link>[[~[[*id]]? &scheme=`full`]]</link>
    <description>[[*introtext:cdata]]</description>
    <language>[[++cultureKey]]</language>
    <ttl>120</ttl>
    <atom:link href="[[~[[*id]]? &scheme=`full`]]" rel="self" type="application/rss+xml" />
    
    [[+rssItems]]

</channel>
</rss>