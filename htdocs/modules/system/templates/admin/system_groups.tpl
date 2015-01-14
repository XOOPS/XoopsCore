<!--groups-->
<{include file="admin:system/admin_breadcrumb.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{if $groups_count|default:false}>
<{include file="admin:system/admin_buttons.tpl"}>
<table id="xo-group-sorter" class="outer tablesorter">
    <thead>
        <tr>
            <th class="txtcenter span1"><{translate key='ID'}></th>
            <th class="txtcenter span3"><{translate key='GROUP_NAME' dirname='system'}></th>
            <th class="txtleft"><{translate key='GROUP_DESCRIPTION' dirname='system'}></th>
            <th class="txtcenter span3"><{translate key='NUMBER_OF_USERS_BY_GROUP' dirname='system'}></th>
            <th class="txtcenter span2"><{translate key='ACTION'}></th>
        </tr>
    </thead>
    <tbody>
        <{foreach item=group from=$groups}>
        <tr class="<{cycle values='odd, even'}> alignmiddle">
            <td class="txtcenter"><{$group.groups_id}></td>
            <td class="txtleft">
                <a class="xo-tooltip" href="admin.php?fct=groups&amp;op=groups_edit&amp;groups_id=<{$group.groups_id}>" title="<{translate key='EDIT_GROUP' dirname='system'}>">
                    <{$group.name}>
                </a>
            </td>
            <td class="txtleft"><{$group.description}></td>
            <td class="txtcenter">
                <a href="./admin.php?fct=users&amp;selgroups=<{$group.groups_id}>"><{$group.nb_users_by_groups}></a>
            </td>
            <td class="xo-actions txtcenter">
                <a class="xo-tooltip" href="admin.php?fct=groups&amp;op=groups_edit&amp;groups_id=<{$group.groups_id}>" title="<{translate key='EDIT_GROUP' dirname='system'}>">
                    <img src="<{xoAdminIcons 'edit.png'}>" alt="<{translate key='EDIT_GROUP' dirname='system'}>" />
                </a>
                <{if $group.delete|default:false}>
                <a class="xo-tooltip" href="admin.php?fct=groups&amp;op=groups_delete&amp;groups_id=<{$group.groups_id}>" title="<{translate key='DELETE_GROUP' dirname='system'}>">
                    <img src="<{xoAdminIcons 'delete.png'}>" alt="<{translate key='DELETE_GROUP' dirname='system'}>" />
                </a>
                <{/if}>
            </td>
        </tr>
        <{/foreach}>
    </tbody>
</table>
<!-- Display groups navigation -->
<div class="clear spacer"></div>
<{if $nav_menu|default:false}>
<div class="xo-avatar-pagenav floatright"><{$nav_menu}></div><div class="clear spacer"></div>
<{/if}>
<{/if}>
<!-- Display groups form (add,edit) -->
<{$form|default:''}>