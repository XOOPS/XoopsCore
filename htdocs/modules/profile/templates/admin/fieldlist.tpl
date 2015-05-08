<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<{if $fieldlist|default:false}>
<form action="field.php" method="post" id="fieldform">
    <table class="outer">
        <thead>
            <tr>
                <th><{$smarty.const._PROFILE_AM_NAME}></th>
                <th><{$smarty.const._PROFILE_AM_TITLE}></th>
                <th><{$smarty.const._PROFILE_AM_DESCRIPTION}></th>
                <th><{$smarty.const._PROFILE_AM_TYPE}></th>
                <th><{$smarty.const._PROFILE_AM_CATEGORY}></th>
                <th><{$smarty.const._PROFILE_AM_WEIGHT}></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <{foreach item=category from=$fieldcategories}>
            <{foreach item=field from=$category}>
                <tr class="<{cycle values='odd, even'}>">
                    <td><{$field.field_name}></td>
                    <td><{$field.field_title}></td>
                    <td><{$field.field_description}></td>
                    <td><{$field.fieldtype}></td>
                    <td>
                        <{if $field.canEdit}>
                            <select name="category[<{$field.field_id}>]"><{html_options options=$categories selected=$field.cat_id}></select>
                        <{/if}>
                    </td>
                    <td>
                        <{if $field.canEdit}>
                            <input class="span2" type="text" name="weight[<{$field.field_id}>]" maxlength="5" value="<{$field.field_weight}>" />
                        <{/if}>
                    </td>
                    <td class="xo-actions txtcenter width5">
                        <{if $field.canEdit}>
                            <input type="hidden" name="oldweight[<{$field.field_id}>]" value="<{$field.field_weight}>" />
                            <input type="hidden" name="oldcat[<{$field.field_id}>]" value="<{$field.cat_id}>" />
                            <input type="hidden" name="field_ids[]" value="<{$field.field_id}>" />
                            <a href="field.php?op=edit&amp;id=<{$field.field_id}>" title="<{translate key ='A_EDIT'}>">
                                <img src="<{xoAdminIcons 'edit.png'}>" alt="<{translate key ='A_EDIT'}>">
                            </a>
                        <{/if}>
                        <{if $field.canDelete}>
                            <a href="field.php?op=delete&amp;id=<{$field.field_id}>" title="<{translate key ='A_DELETE'}>">
                                <img src="<{xoAdminIcons 'delete.png'}>" alt="<{translate key ='A_DELETE'}>">
                            </a>
                        <{/if}>
                    </td>
                </tr>
            <{/foreach}>
        <{/foreach}>
        </tbody>
        <tfoot>
            <tr class="<{cycle values='odd, even'}>">
                <td colspan="5">
                </td>
                <td colspan="2">
                    <{$token}>
                    <input type="hidden" name="op" value="reorder" />
                    <input class="btn primary" type="submit" name="submit" value="<{translate key='A_SUBMIT'}>" />
                </td>
            </tr>
        </tfoot>
    </table>
</form>
<{/if}>
<!-- Display form (add,edit) -->
<{$form|default:''}>