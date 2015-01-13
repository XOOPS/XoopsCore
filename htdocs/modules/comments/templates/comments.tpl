<div style="text-align: center; padding: 0px; margin:0px;">
    <form class="form-inline" method="get" action="<{$page_name}>">
        <select class="span2" name="com_mode">
            <option value="flat" <{if $comment_mode == "flat"}>selected="selected"<{/if}>><{translate key="FLAT"}></option>
            <option value="thread" <{if $comment_mode == "thread"}>selected="selected"<{/if}>><{translate key="THREADED"}></option>
            <option value="nest" <{if $comment_mode == "nest"}>selected="selected"<{/if}>><{translate key="NESTED"}></option>
        </select>
        <select class="span2" name="com_order">
            <option value="<{$COMMENTS_OLD1ST}>" <{if $order == $COMMENTS_OLD1ST}>selected="selected"<{/if}>><{translate key="OLDEST_FIRST"}></option>
            <option value="<{$COMMENTS_NEW1ST}>" <{if $order == $COMMENTS_NEW1ST}>selected="selected"<{/if}>><{translate key="NEWEST_FIRST"}></option>
        </select>
        <input type="hidden" name="<{$item_name}>" value="<{$itemid}>" />
        <{if $extra_param|default:false}>
        <input type="hidden" name="<{$extra_param}>" value="<{$hidden_value}>" />
        <{/if}>
        <button type="submit" class="btn"><{$smarty.const._MD_COMMENTS_REFRESH}></button>
        <{if $postcomment_link}>
        <button type="button" class="btn" onclick="self.location.href='<{$postcomment_link}><{$link_extra}>'"><{$smarty.const._MD_COMMENTS_POSTCOMMENT}></button>
        <{/if}>
    </form>
    <{$comments_lang_notice}>
</div>
<div style="margin:3px; padding: 3px;">
    <!-- start comments loop -->
    <{if $comment_mode == "flat"}>
    <{include file="module:comments/comments_flat.tpl"}>
    <{elseif $comment_mode == "thread"}>
    <{include file="module:comments/comments_thread.tpl"}>
    <{elseif $comment_mode == "nest"}>
    <{include file="module:comments/comments_nest.tpl"}>
    <{/if}>
    <!-- end comments loop -->
</div>