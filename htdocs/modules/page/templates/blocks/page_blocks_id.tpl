<{if $block.content_dotitle && $block.content_dorating}>
    <div class="page_header">
        <{if $block.content_dotitle}>
            <div class="page_headerleft">
                <{$block.content_title}>
            </div>
        <{/if}>
        <{if $block.content_dorating}>
            <div class="page_headerright">
                <{include file="module:page/page_rating.tpl" yourvote=$block.yourvote security=$block.security}>
            </div>
        <{/if}>
        <div class="clear"></div>
    </div>

    <{if $block.content_dorating}>
        <div class="page_vote">
            <{translate key="RATING"}>:&nbsp;<span class="average"><{$block.content_rating}></span>&nbsp;(<span class="voters"><{$block.content_votes}></span>&nbsp;<{translate key="VOTES"}>)
            <span class="yourvote<{if $block.yourvote < 0}> hide<{/if}>">
                &nbsp;<{translate key="YOUR_VOTE" dirname="page"}>&nbsp;:<span class="vote"><{$block.yourvote}></span>
            </span>
        </div>
    <{/if}>
<{/if}>
<div class="page_content">
    <{if $block.related.related_domenu}>
        <h4><{translate key="SUMMARY"}>:</h4>
        <div>
            <ul>
                <{foreach item=summary from=$block.related.related_links}>
                    <li><a href="<{xoAppUrl 'modules/page/viewpage.php'}>?id=<{$summary.content_id}>" title="<{$summary.content_title}>"><{$summary.content_title}></a></li>
                <{/foreach}>
            </ul>
        </div>
    <{/if}>
    <{$block.content_shorttext}>
    <br />
    <{$block.content_text}>
    <{if $block.related.related_navigation}>
        <div class="clear"></div>
            <{include file="module:page/page_navigation.tpl" related=$block.related}>
        <div class="clear"></div>
    <{/if}>

    <div class="right">
        <{if $block.content_doprint}>
            <a href="<{$xoops_url}>/modules/page/print.php?id=<{$block.content_id}>" title="<{translate key="PRINT_THIS_PAGE"}>"><img src="<{xoAppUrl 'media/xoops/images/icons/16/printer.png'}>" alt="<{translate key="PRINT_THIS_PAGE"}>" /></a>
        <{/if}>
        <{if $block.content_dopdf}>
            <a href="<{$xoops_url}>/modules/page/pdf.php?id=<{$block.content_id}>" title="<{translate key="MAKE_PDF_FROM_THIS_PAGE"}>"><img src="<{xoAppUrl 'media/xoops/images/icons/16/pdf.png'}>" alt="<{translate key="MAKE_PDF_FROM_THIS_PAGE"}>" /></a>
        <{/if}>
    </div>
</div>
<{if $block.content_doauthor && $block.content_dodate && $block.content_dohits && $block.content_docoms && $block.content_doncoms}>
    <div class="page_footer">
        &nbsp;
        <div class="page_footerleft">
            <{if $block.content_doauthor}>
                <{translate key="AUTHOR"}>&nbsp;<a href="<{$xoops_url}>/userinfo.php?uid=<{$block.content_authorid}>"><{$block.content_author}></a>&nbsp;
            <{/if}>
            <{if $block.content_dodate}>
                <{translate key="PUBLISHED"}>&nbsp;<{$block.content_date}>&nbsp;<{$block.content_time}>&nbsp;
            <{/if}>
            <{if $block.content_dohits}>
                (<{$block.content_hits}>&nbsp;<{translate key="READS"}>)
            <{/if}>
        </div>
        <{if $block.content_docoms && $block.content_doncoms}>
            <div class="page_footerright">
                <img src="<{$xoops_url}>/modules/page/images/comments.png" alt="<{$block.content_comments}>"/>&nbsp;(<{$block.content_comments}>&nbsp;<{translate key="COMMENTS"}>)
            </div>
        <{/if}>
    </div>
<{/if}>