<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<!--Comments-->
<{if $form|default:false}>
<{$form}>
<{else}>
<div class="floatleft"><{$form_sort}></div>
<div class="floatright">
    <div class="xo-buttons">
        <button class="btn" onclick="self.location.href='main.php?op=comments_form_purge'">
            <i class="icon-remove"></i>
            <{$smarty.const._AM_COMMENTS_FORM_PURGE}>
        </button>
    </div>
</div>
<div class="clear"></div>
<form name='commentslist' id='commentslist' action='<{$php_selft}>' method="post">
    <table id="xo-comment-sorter" class="outer tablesorter">
        <thead>
        <tr>
            <th class="txtcenter width5"><input name='allbox' id='allbox' onclick='xoopsCheckAll("commentslist", "allbox");'  type='checkbox' value='Check All' /></th>
            <th class="txtcenter width5"></th>
            <th class="txtcenter"><{$smarty.const._AM_COMMENTS_TITLE}></th>
            <th class="txtcenter"><{$smarty.const._AM_COMMENTS_POSTED}></th>
            <th class="txtcenter"><{$smarty.const._AM_COMMENTS_IP}></th>
            <th class="txtcenter"><{translate key="DATE"}></th>
            <th class="txtcenter"><{$smarty.const._AM_COMMENTS_MODULE}></th>
            <th class="txtcenter"><{$smarty.const._AM_COMMENTS_STATUS}></th>
            <th class="txtcenter width10"><{$smarty.const._AM_COMMENTS_ACTION}></th>
        </tr>
        </thead>
        <tbody>
        <{if $comments|default:false}>
        <{foreach item=comment from=$comments}>
        <tr class="<{cycle values='even,odd'}> alignmiddle">
            <td class="txtcenter"><input type='checkbox' name='commentslist_id[]' id='commentslist_id[]' value='<{$comment.comments_id}>'/></td>
            <td class="txtcenter"><{$comment.comments_icon}></td>
            <td class="txtcenter"><{$comment.comments_title}></td>
            <td class="txtcenter"><{$comment.comments_poster}></td>
            <td class="txtcenter"><{$comment.comments_ip}></td>
            <td class="txtcenter"><{$comment.comments_date}></td>
            <td class="txtcenter"><{$comment.comments_modid}></td>
            <td class="txtcenter"><{$comment.comments_status}></td>
            <td class="xo-actions txtcenter">
                <img class="cursorpointer" onclick="display_dialog('<{$comment.comments_id}>', true, true, 'slide', 'slide', 300, 500);" src="<{xoAdminIcons 'display.png'}>" alt="<{$smarty.const._AM_COMMENTS_VIEW}>" title="<{$smarty.const._AM_COMMENTS_VIEW}>" />
                <a href="comment_edit.php?com_id=<{$comment.comments_id}>" title="<{translate key='A_EDIT'}>">
                    <img src="<{xoAdminIcons 'edit.png'}>" alt="<{translate key='A_EDIT'}>">
                </a>
                <a href="comment_delete.php?com_id=<{$comment.comments_id}>" title="<{translate key='A_DELETE'}>">
                    <img src="<{xoAdminIcons 'delete.png'}>" alt="<{translate key ='A_DELETE'}>">
                </a>
            </td>
        </tr>
        <{/foreach}>
        <{/if}>
        </tbody>
        <tr>
            <td><input class='btn' type='submit' name='<{translate key="A_DELETE"}>' value='<{translate key="A_DELETE"}>' /></td>
            <td colspan="8">&nbsp;</td>
        </tr>
    </table>
</form>
<{if $comments_popup|default:false}>
<{foreach item=comments from=$comments_popup}>
<!--Pop-pup-->
<div id='dialog<{$comments.comments_id}>' title='<{$comments.comments_icon}>&nbsp;&nbsp;<{$comments.comments_title}>' style='display:none;'>
    <img src="<{xoAdminIcons 'comment.png'}>" alt="comments" title="comments" class="xo-commentsimg" />
    <p><{$comments.comments_text}></p>
</div>
<{/foreach}>
<{/if}>
<!--Pop-pup-->
<div class="txtright"><{$nav|default:''}></div>
<{/if}>
