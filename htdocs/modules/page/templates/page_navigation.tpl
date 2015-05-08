<{if $related.related_navigation == 1}>
    <div class="page_navigation" style="width: 250px;">
        <{if $related.prev_id != 0}>
            <div class="page_navigationL">
                <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$related.prev_id}>" title="<{$related.prev_title}>"><img src="<{$xoops_url}>/modules/page/images/previous.png" alt="<{$related.prev_title}>"/></a>
            </div>
        <{/if}>
        <{if $related.next_id != 0}>
            <div class="page_navigationR">
                <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$related.next_id}>" title="<{$related.next_title}>"><img src="<{$xoops_url}>/modules/page/images/next.png" alt="<{$related.next_title}>"/></a>
            </div>
        <{/if}>
    </div>
<{/if}>

<{if $related.related_navigation == 2}>
    <div class="page_navigation">
        <form class="form-inline" name='nav_form' id='nav_form' action='<{$xoops_url}>/modules/page/viewpage.php' method='get'>
            <{if $related.prev_id != 0}>
            <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$related.prev_id}>" title="<{$related.prev_title}>"><img src="<{$xoops_url}>/modules/page/images/previous.png" alt="<{$related.prev_title}>"/></a>
            <{/if}>
            <select class="span2" size="1" onchange="document.forms.nav_form.submit()" name="id" id="select_nav" title="">
                <{foreach item=summary from=$related.related_links}>
                    <{if $summary.content_id == $content_id}>
                        <option value="<{$summary.content_id}>" selected="selected"><{$summary.content_title}></option>
                    <{else}>
                        <option value="<{$summary.content_id}>"><{$summary.content_title}></option>
                    <{/if}>
                <{/foreach}>
            </select>
            <{if $related.next_id != 0}>
            <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$related.next_id}>" title="<{$related.next_title}>"><img src="<{$xoops_url}>/modules/page/images/next.png" alt="<{$related.next_title}>"/></a>
            <{/if}>
        </form>
    </div>
<{/if}>

<{if $related.related_navigation == 3}>
    <div class="page_navigation" style="width: 250px;">
        <{if $related.prev_id != 0}>
            <div class="page_navigationL">
                <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$related.prev_id}>" title="<{$related.prev_title}>"><img src="<{$xoops_url}>/modules/page/images/previous.png" alt="<{$related.prev_title}>"/>&nbsp;<{$related.prev_title}></a>
            </div>
        <{/if}>
        <{if $related.next_id != 0}>
            <div class="page_navigationR">
                <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$related.next_id}>" title="<{$related.next_title}>"><{$related.next_title}>&nbsp;<img src="<{$xoops_url}>/modules/page/images/next.png" alt="<{$related.next_title}>"/></a>
            </div>
        <{/if}>
    </div>
<{/if}>

<{if $related.related_navigation == 4}>
    <div class="page_navigation" style="width: 250px;">
        <form class="form-inline" name='nav_form' id='nav_form' action='<{$xoops_url}>/modules/page/viewpage.php' method='get'>
            <select class="span2" size="1" onchange="document.forms.nav_form.submit()" name="id" id="select_nav" title="">
                <{foreach item=summary from=$related.related_links}>
                    <{if $summary.content_id == $content_id}>
                        <option value="<{$summary.content_id}>" selected="selected"><{$summary.content_title}></option>
                    <{else}>
                        <option value="<{$summary.content_id}>"><{$summary.content_title}></option>
                    <{/if}>
                <{/foreach}>
            </select>
        </form>
    </div>
<{/if}>

<{if $related.related_navigation == 5}>
    <ul class="pager">
        <{if $related.prev_id != 0}>
            <li class="previous">
            <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$related.prev_id}>" title="<{$related.prev_title}>"><{$related.prev_title}></a>
            </li>
        <{/if}>
        <{if $related.next_id != 0}>
            <li class="next">
                <a href="<{$xoops_url}>/modules/page/viewpage.php?id=<{$related.next_id}>" title="<{$related.next_title}>"><{$related.next_title}></a>
            </li>
        <{/if}>
    </ul>
<{/if}>