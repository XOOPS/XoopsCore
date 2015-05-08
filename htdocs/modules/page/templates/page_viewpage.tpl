<{if $content_dotitle|default:false && $content_dorating|default:false}>
    <div class="page_header">
        &nbsp;
        <{if $content_dotitle}>
            <div class="page_headerleft">
                <{$content_title}>
            </div>
        <{/if}>
        <{if $content_dorating}>
            <div class="page_headerright">
                <{include file="module:page/page_rating.tpl"}>
            </div>
        <{/if}>
        <div class="clear"></div>
    </div>

    <{if $content_dorating|default:false}>
        <div class="page_vote">
            <{translate key="RATING"}>:&nbsp;<span class="average"><{$content_rating}></span>&nbsp;(<span class="voters"><{$content_votes}></span>&nbsp;<{translate key="VOTES"}>)
            <span class="yourvote<{if $yourvote < 0}> hide<{/if}>">
                &nbsp;<{translate key="YOUR_VOTE" dirname="page"}>&nbsp;:<span class="vote"><{$yourvote}></span>
            </span>
        </div>
    <{/if}>
<{/if}>
<div class="page_content">
    <{if $related.related_domenu|default:false}>
        <h4><{translate key="SUMMARY"}>:</h4>
        <div>
            <ul>
                <{foreach item=summary from=$related.related_links}>
                    <li><a href="<{xoAppUrl 'modules/page/viewpage.php'}>?id=<{$summary.content_id}>" title="<{$summary.content_title}>"><{$summary.content_title}></a></li>
                <{/foreach}>
            </ul>
        </div>
    <{/if}>
    <{$content_shorttext}>
    <br />
    <{$content_text}>
    <{if $related.related_navigation|default:false}>
        <div class="clear"></div>
            <{include file="module:page/page_navigation.tpl"}>
        <div class="clear"></div>
    <{/if}>

    <div class="right">
        <{if $content_doprint|default:false}>
            <a href="<{$xoops_url}>/modules/page/print.php?id=<{$content_id}>" title="<{translate key="PRINT_THIS_PAGE"}>"><img src="<{xoAppUrl 'media/xoops/images/icons/16/printer.png'}>" alt="<{translate key="PRINT_THIS_PAGE"}>" /></a>
        <{/if}>
        <{if $content_dopdf|default:false}>
            <a href="<{$xoops_url}>/modules/page/pdf.php?id=<{$content_id}>" title="<{translate key="MAKE_PDF_FROM_THIS_PAGE"}>"><img src="<{xoAppUrl 'media/xoops/images/icons/16/pdf.png'}>" alt="<{translate key="MAKE_PDF_FROM_THIS_PAGE"}>" /></a>
        <{/if}>
    </div>
</div>
<{if $content_doauthor|default:false || $content_dodate|default:false || $content_dohits|default:false || $content_docoms|default:false || $content_doncoms|default:false}>
    <div class="page_footer">
        &nbsp;
        <div class="page_footerleft">
            <{if $content_doauthor|default:false}>
                <{translate key="AUTHOR"}>&nbsp;<a href="<{$xoops_url}>/userinfo.php?uid=<{$content_authorid}>"><{$content_author}></a>&nbsp;
            <{/if}>
            <{if $content_dodate|default:false}>
                <{translate key="PUBLISHED"}>&nbsp;<{$content_date}>&nbsp;<{$content_time}>&nbsp;
            <{/if}>
            <{if $content_dohits|default:false}>
                (<{$content_hits}>&nbsp;<{translate key="READS"}>)
            <{/if}>
        </div>
        <{if $content_docoms|default:false && $content_doncoms|default:false}>
            <div class="page_footerright">
                <img src="<{$xoops_url}>/modules/page/images/comments.png" alt="<{$content_comments}>"/>&nbsp;(<{$content_comments}>&nbsp;<{translate key="COMMENTS"}>)
            </div>
        <{/if}>
    </div>
<{/if}>

<{if $content_dosocial|default:false}>
    <{include file='module:xoosocialnetwork/xoosocialnetwork.tpl'}>
<{/if}>
<{if $content_docoms|default:false}>
    <{include file='module:comments/comments.tpl'}>
<{/if}>
<{if $content_donotifications|default:false}>
    <{include file='module:notifications/select.tpl'}>
<{/if}>