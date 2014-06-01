<div class="item">
    <div class="itemHead">
        <span class="itemTitle"><{$item.titlelink}></span>
    </div>

    <{if $op != 'preview'}> <{if $display_whowhen_link}>
    <div class="itemInfo">
	    <span class="itemPoster">
      	  <div class="publisher_item_head_who">
                <{$item.who_when}> (<{$item.counter}> <{$lang_reads}>)
            </div>
        </span>
    </div>
    <{/if}> <{/if}>

    <div class="itemBody">
        <{if $item.image_path}>
        <a href="<{$item.itemurl}>" title="<{$item.title}>"><img class="publisher_item_image" src="<{$item.image_path}>" align="right" alt="<{$item.title}>" width="100" /></a>
        <{/if}>
        <div class="itemText"><{$item.summary}></div>
    </div>

    <{if $op != 'preview' && $item.body}>
    <div align="right">
        <a href="<{$item.itemurl}>"> <{$smarty.const._MD_PUBLISHER_VIEW_MORE}></a>&nbsp;
    </div>
    <{/if}>

    <div class="publisher_pre_itemInfo">
        <div class="itemInfo" style="height: 14px;">

            <{if $display_comment_link && $item.cancomment && $item.comments != -1 && $com_rule <> 0}>
            <span style="float: left;"><a href="<{$item.itemurl}>"><{$item.comments}> <{$smarty.const._MD_PUBLISHER_COMMENTS}></a></span> <{else}>
            <span style="float: left;">&nbsp;</span> <{/if}> <{if $op <> 'preview'}>
            <span style="float: right; text-align: right;"><{$item.adminlink}></span> <{else}>
            <span style="float: right;">&nbsp;</span> <{/if}>
            <div style="height: 0; display: inline; clear: both;"></div>
        </div>
    </div>
</div><br/>
