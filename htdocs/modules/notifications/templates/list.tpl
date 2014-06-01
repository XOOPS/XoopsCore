<fieldset>
    <legend><{$lang_activenotifications}></legend>
    <form name="notificationlist" action="index.php" method="post">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><input name="allbox" id="allbox" onclick="xoopsCheckAll('notificationlist', 'allbox');" type="checkbox" value="<{$lang_checkall}>" /></th>
                    <th><{$lang_event}></th>
                    <th><{$lang_category}></th>
                    <th><{$lang_itemid}></th>
                    <th><{$lang_itemname}></th>
                </tr>
            </thead>
            <tbody>
                <{foreach item=module from=$modules}>
                <tr>
                    <td><input name="del_mod[<{$module.id}>]" id="del_mod[]" onclick="xoopsCheckGroup('notificationlist', 'del_mod[<{$module.id}>]', 'del_not[<{$module.id}>][]');" type="checkbox" value="<{$module.id}>" /></td>
                    <td colspan="4"><{$lang_module}>: <{$module.name}></td>
                </tr>
                <{foreach item=category from=$module.categories}>
                <{foreach item=item from=$category.items}>
                <{foreach item=notification from=$item.notifications}>
                <tr>
                    <td><input type="checkbox" name="del_not[<{$module.id}>][]" id="del_not[<{$module.id}>]" value="<{$notification.id}>" /></td>
                    <td><{$notification.event_title}></td>
                    <td><{$notification.category_title}></td>
                    <td><{if $item.id != 0}><{$item.id}><{/if}></td>
                    <td><{if $item.id != 0}><{if $item.url != ''}><a href="<{$item.url}>" title="<{$item.name}>"><{/if}><{$item.name}><{if $item.url != ''}></a><{/if}><{/if}></td>
                </tr>
                <{/foreach}>
                <{/foreach}>
                <{/foreach}>
                <{/foreach}>
            </tbody>
        </table>
        <input type="submit" name="delete_cancel" value="<{$lang_cancel}>" class="btn"/>
        <input type="reset" name="delete_reset" value="<{$lang_clear}>" class="btn"/>
        <input type="submit" name="delete" value="<{$lang_delete}>" class="btn btn-danger"/>
        <input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<{$notification_token}>" />
    </form>
</fieldset>