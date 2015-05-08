<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<{if $category|default:false}>
<table class="outer">
    <thead>
        <tr>
            <th class="txtcenter"><{$smarty.const._PROFILE_AM_TITLE}></th>
            <th class="txtleft"><{$smarty.const._PROFILE_AM_DESCRIPTION}></th>
            <th><{$smarty.const._PROFILE_AM_WEIGHT}></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <{foreach item=category from=$categories}>
        <tr class="<{cycle values='odd, even'}>">
            <td class="txtcenter width20"><{$category.cat_title}></td>
            <td class="txtleft"><{$category.cat_description}></td>
            <td class="txtcenter width5"><{$category.cat_weight}></td>
            <td class="xo-actions txtcenter width5">
                <a href="category.php?op=edit&amp;id=<{$category.cat_id}>" title="<{translate key ='A_EDIT'}>">
                    <img src="<{xoAdminIcons 'edit.png'}>" alt="<{translate key ='A_EDIT'}>">
                </a>
                <a href="category.php?op=delete&amp;id=<{$category.cat_id}>" title="<{translate key ='A_DELETE'}>">
                    <img src="<{xoAdminIcons 'delete.png'}>" alt="<{translate key ='A_DELETE'}>">
                </a>
            </td>
        </tr>
        <{/foreach}>
    </tbody>
</table>
<{/if}>
<!-- Display form (add,edit) -->
<{$form|default:''}>