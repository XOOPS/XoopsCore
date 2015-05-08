<{foreach item=content from=$block.content}>
    <div class="page_indexheader">
        &nbsp;
        <div class="page_headerleft">
            <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$content.content_id}>" title="<{$content.content_title}>"><{$content.content_title}></a>
        </div>
    </div>
    <div class="page_indexcontent">
        <{$content.content_shorttext}>
        <{if $block.text}>
            <br />
            <{$content.content_text}>
        <{else}>
        <div class="page_more">
            <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$content.content_id}>" title="<{$content.content_title}>"><{translate key="MORE_DETAILS"}></a>
        </div>
    <{/if}>
</div>
<div class="page_footer">
    &nbsp;
    <div class="page_footerleft">
        <{translate key="AUTHOR"}>&nbsp;<a href="<{$xoops_url}>/userinfo.php?uid=<{$content.content_authorid}>"><{$content.content_author}></a>&nbsp;<{translate key="PUBLISHED"}>&nbsp;<{$content.content_date}>
    </div>
</div>
<{/foreach}>