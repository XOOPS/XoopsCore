<!--groups-->
<{includeq file="admin:system|admin_breadcrumb.tpl"}>
<{includeq file="admin:system|admin_tips.tpl"}>
<{if $groups_count == true}>
<{includeq file="admin:system|admin_buttons.tpl"}>
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
        <{foreach item=groups from=$groups}>
        <tr class="<{cycle values='odd, even'}> alignmiddle">
            <td class="txtcenter"><{$groups.groups_id}></td>
            <td class="txtleft">
                <a class="xo-tooltip" href="admin.php?fct=groups&amp;op=groups_edit&amp;groups_id=<{$groups.groups_id}>" title="<{translate key='EDIT_GROUP' dirname='system'}>">
                    <{$groups.name}>
                </a>
            </td>
            <td class="txtleft"><{$groups.description}></td>
            <td class="txtcenter">
                <a href="./admin.php?fct=users&amp;selgroups=<{$groups.groups_id}>"><{$groups.nb_users_by_groups}></a>
            </td>
            <td class="xo-actions txtcenter">
                <a class="xo-tooltip" href="admin.php?fct=groups&amp;op=groups_edit&amp;groups_id=<{$groups.groups_id}>" title="<{translate key='EDIT_GROUP' dirname='system'}>">
                    <img src="<{xoAdminIcons edit.png}>" alt="<{translate key='EDIT_GROUP' dirname='system'}>" />
                </a>
                <{if $groups.delete}>
                <a class="xo-tooltip" href="admin.php?fct=groups&amp;op=groups_delete&amp;groups_id=<{$groups.groups_id}>" title="<{translate key='DELETE_GROUP' dirname='system'}>">
                    <img src="<{xoAdminIcons delete.png}>" alt="<{translate key='DELETE_GROUP' dirname='system'}>" />
                </a>
                <{/if}>
            </td>
        </tr>
        <{/foreach}>
    </tbody>
</table>
<!-- Display groups navigation -->
<div class="clear spacer"></div>
<{if $nav_menu}>
<div class="xo-avatar-pagenav floatright"><{$nav_menu}></div><div class="clear spacer"></div>
<{/if}>
<{/if}>
<!-- Display groups form (add,edit) -->
<{$form}>
