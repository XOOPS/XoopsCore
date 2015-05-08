<form action="<{$block.target_page}>" method="post">
    <table class="outer">
        <{foreach item=category from=$block.categories}>
        <{foreach name=inner item=event from=$category.events}>
            <{if $smarty.foreach.inner.first}>
                <tr>
                    <td class="head" colspan="2"><{$category.title}></td>
                </tr>
                <{/if}>
            <tr>
                <td class="odd">
                    <{counter assign=index}>
                    <input type="hidden" name="not_list[<{$index}>][params]"
                           value="<{$category.name}>,<{$category.itemid}>,<{$event.name}>"/>
                    <input type="checkbox" name="not_list[<{$index}>][status]" value="1" <{if $event.subscribed}>checked="checked"<{/if}> />
                </td>
                <td class="odd"><{$event.caption}></td>
            </tr>
            <{/foreach}>
        <{/foreach}>
        <tr>
            <td class="foot" colspan="2">
                <input type="hidden" name="not_redirect" value="<{$block.redirect_script}>">
                <input type="hidden" value="<{$block.notification_token}>" name="XOOPS_TOKEN_REQUEST"/>
                <input type="submit" name="not_submit" value="<{$block.submit_button}>"/>
            </td>
        </tr>
    </table>
</form>