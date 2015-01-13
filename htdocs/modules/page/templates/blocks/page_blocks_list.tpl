<ul>
    <{foreach item=content from=$block.content}>
        <li>
            <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$content.content_id}>" title="<{$content.content_title}>"><{$content.content_title}></a>
            &nbsp;-&nbsp;<{translate key="AUTHOR"}>:&nbsp;<a href="<{$xoops_url}>/userinfo.php?uid=<{$content.content_authorid}>"><{$content.content_author}></a>
            &nbsp;-&nbsp;<{translate key="PUBLISHED"}>:&nbsp;<{$content.content_date}>
            &nbsp;-&nbsp;<{translate key="READS"}>:&nbsp;<{$content.content_hits}>
            &nbsp;-&nbsp;<{translate key="RATING"}>:&nbsp;<{$content.content_rating}>
        </li>
    <{/foreach}>
</ul>