<script type="text/javascript">
    Xoops.setStatusImg('accept', '<{xoAdminIcons success.png}>');
    Xoops.setStatusImg('cancel', '<{xoAdminIcons cancel.png}>');
    Xoops.setStatusText('accept', '<{translate key="A_DISABLE"}>');
    Xoops.setStatusText('cancel', '<{translate key="A_ENABLE"}>');
</script>
<{includeq file="admin:system|admin_navigation.tpl"}>
<{includeq file="admin:system|admin_tips.tpl"}>
<{includeq file="admin:system|admin_buttons.tpl"}>
<{if $form}>
    <{$form}>
<{/if}>
<!--Page content-->
<{if $content_count == true}>
<table id="xo-smilies-sorter" class="outer tablesorter">
    <thead>
    <tr>
        <th class="txtcenter"><{translate key="ID"}></th>
        <th class="txtleft"><{translate key="TITLE"}></th>
        <th class="txtleft"><{translate key="CONTENT_SELECT_GROUPS"}></th>
    </tr>
    </thead>
    <tbody>
    <{foreach item=content from=$content}>
    <tr class="<{cycle values='even,odd'}> alignmiddle">
        <td class="txtcenter width5"><{$content.id}></td>
        <td class="txtleft width45"><{$content.title}></td>
        <td class="xo-actions txtleft">
            <{$content.permissions}>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>
<div class="clear spacer"></div>
<{if $nav_menu}>
<div class="xo-avatar-pagenav floatright"><{$nav_menu}></div><div class="clear spacer"></div>
<{/if}>
<{/if}>
<!-- Display form (add,edit) -->
<{if $error_message}>
<div class="alert alert-error">
    <strong><{$error_message}></strong>
</div>
<{/if}>
