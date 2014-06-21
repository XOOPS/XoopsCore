<?xml version="1.0" encoding="<{$channel_charset}>"?>
<rss version="2.0">
  <channel>
    <title><{$channel_title}></title>
    <link><{$channel_link}></link>
    <description><{$channel_description}></description>
    <copyright><{$channel_copyright}></copyright>
    <lastBuildDate><{$channel_lastbuild}></lastBuildDate>
    <docs>http://backend.userland.com/rss/</docs>
    <generator><{$channel_generator}></generator>
    <category><{$channel_category}></category>
    <managingEditor><{$channel_editor}></managingEditor>
    <webMaster><{$channel_webmaster}></webMaster>
    <ttl><{$channel_ttl}></ttl>
    <language><{$channel_language}></language>
    <{if $channel_image_url != ""}>
    <image>
      <title><{$channel_image_title}></title>
      <url><{$channel_image_url}></url>
      <link><{$channel_image_link}></link>
      <width><{$channel_image_width}></width>
      <height><{$channel_image_height}></height>
    </image>
    <{/if}>
    <{foreach item=item from=$items}>
    <item>
      <title><{$item.title}></title>
      <link><{$item.link}></link>
      <description><{$item.description}></description>
      <pubDate><{$item.pubdate}></pubDate>
      <guid><{$item.guid}></guid>
      <category><{$item.category}></category>
      <author><{$item.author}></author>
    </item>
    <{/foreach}>
  </channel>
</rss>