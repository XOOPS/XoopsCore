<fieldset>
    <legend><{$lang_activenotifications|default:''}></legend>
    <{if $notifications.show|default:false}>
    <form name="notification_select" action="<{$notifications.target_page}>" method="post">
        <input type="hidden" name="not_redirect" value="<{$notifications.redirect_script}>"/>
        <input type="hidden" name="mid" value="<{$notifications.mid}>"/>
        <{securityToken}>
        <table class="table table-bordered">
            <tr>
                <th style="text-align: center;" colspan="3"><{$lang_notificationoptions}></th>
            </tr>
            <tr>
                <td><{$lang_category}></td>
                <td>
                    <input name="allbox" id="allbox" onclick="xoopsCheckAll('notification_select','allbox');"
                                        type="checkbox" value="<{$lang_checkall}>"/>
                </td>
                <td><{$lang_events}></td>
            </tr>
            <{foreach name=outer item=category from=$notifications.categories}>
            <{foreach name=inner item=event from=$category.events}>
                <tr>
                    <{if $smarty.foreach.inner.first}>
                    <td rowspan="<{$smarty.foreach.inner.total}>"><{$category.title}></td>
                    <{/if}>
                    <td>
                        <{counter assign=index}>
                        <input type="hidden" name="not_list[<{$index}>][params]"
                               value="<{$category.name}>,<{$category.itemid}>,<{$event.name}>"/>
                        <input type="checkbox" id="not_list[]" name="not_list[<{$index}>][status]" value="1"
                        <{if $event.subscribed}>checked="checked"<{/if}> />
                    </td>
                    <td><{$event.caption}></td>
                </tr>
                <{/foreach}>
            <{/foreach}>
            <tr>
                <td style="text-align: center;" colspan="3">
                    <button type="submit" name="not_submit" value="not_submit" class="btn"><{$lang_updatenow}></button>
                </td>
            </tr>
        </table>
        <div class="txtcenter">
            <{$lang_notificationmethodis}>:&nbsp;<{$user_method}>&nbsp;&nbsp;[<a href="<{$editprofile_url}>" title="<{$lang_change}>"><{$lang_change}></a>]
        </div>
    </form>
    <{/if}>
</fieldset>