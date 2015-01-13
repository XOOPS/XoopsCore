<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>

<{if $count}>
<div style="margin-bottom:10px; float: right">
    <form class="form-inline" action="admin_menus.php?op=list" method="POST">
     <input type="text" name="query" id="query" class="span2" value="<{$query}>" />
        <button type="submit" class="btn" name="btn" ><{translate key='A_SEARCH'}></button>
        <button type="submit" class="btn" name="btn" onclick="document.getElementById('query').value='';"><{translate key='A_CANCEL'}></button>
    </form>
</div>

<table id="xo-menus-sorter" class="outer tablesorter">
    <thead>
        <tr>
            <th class="txtleft"><{$smarty.const._AM_MENUS_MENU_TITLE}></th>
            <th class="txtcenter width10"><{translate key='OPTIONS'}></th>
        </tr>
    </thead>
    <tbody>
        <{foreach item=obj key=key from=$objs}>
            <tr class="<{cycle values="even,odd"}>">
                <td class="txtleft"><{$obj.title}></td>
                <td class="xo-actions txtcenter width10">
                    <a href="admin_menu.php?op=list&amp;menu_id=<{$obj.id}>">
                    <img src="<{xoAdminIcons 'view.png'}>" alt="<{$smarty.const._AM_MENUS_ACTION_GOTO_MENU}>">
                    </a>
                    <a href="admin_menus.php?op=edit&amp;id=<{$obj.id}>">
                    <img src="<{xoAdminIcons 'edit.png'}>" alt="<{$smarty.const._AM_MENUS_EDIT_MENUS}>">
                    </a>
                    <a href="admin_menus.php?op=del&amp;id=<{$obj.id}>">
                    <img src="<{xoAdminIcons 'delete.png'}>" alt="<{translate key='A_DELETE'}>">
                    </a>
                </td>
            </tr>
        <{/foreach}>
    </tbody>
</table>
<{if $nav_menu}>
<{$nav_menu}>
<{/if}>
<{/if}>
<{if $error_message|default:false}>
<div class="alert alert-error">
    <strong><{$error_message}></strong>
</div>
<{/if}>
<{$form|default:''}>

