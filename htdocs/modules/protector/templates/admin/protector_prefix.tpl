<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{if $error}>
    <div class="errorMsg">
        <{$error}>
    </div>
<{/if}>

<{if $aff_table}>
<table id="xo-advisorylist-sorter" class="outer tablesorter">
    <thead>
    <tr>
        <th class="txtcenter width10"><{$smarty.const._AM_PROTECTOR_PREFIX}></th>
        <th class="txtcenter width5"><{$smarty.const._AM_PROTECTOR_PREFIX_TABLES}></th>
        <th class="txtcenter"><{$smarty.const._AM_PROTECTOR_PREFIX_UPDATED}></th>
        <th class="txtcenter width25"><{$smarty.const._AM_PROTECTOR_PREFIX_COPY}></th>
        <th class="txtcenter width25"><{$smarty.const._AM_PROTECTOR_PREFIX_ACTIONS}></th>
    </tr>
    </thead>
    <tbody>
    <{foreach item=table from=$table}>
    <{if $table.del}>
        <tr class="even">
    <{else}>
        <tr class="odd">
    <{/if}>
        <td class="txtcenter"><{$table.prefix}></td>
        <td class="txtcenter"><{$table.count}></td>
        <td class="txtcenter"><{$table.update}></td>
        <td class="txtcenter">
            <form class="form-inline" action='prefix_manager.php' method='post' style='margin:0px;'>
                <{$table.ticket}>
                <input type='hidden' name='old_prefix' value='<{$table.prefix}>' />
                <input type='text' name='new_prefix' size='8' maxlength='16' />
                <input class="btn" type='submit' name='copy' value='copy' />
            </form>
        </td>
        <td class="txtcenter">
            <form action='prefix_manager.php' method='post' style='margin:0px;'>
                <{$table.ticket}>
                <input type='hidden' name='prefix' value='<{$table.prefix}>' />
                <{if $table.del}>
                <input class="btn" type='submit' name='delete' value='delete' onclick='return confirm("<{$smarty.const._AM_CONFIRM_DELETE}>")' />
                <{/if}>
                <input class="btn" type='submit' name='backup' value='backup' onclick='this.form.target="_blank"' />
            </form>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>
<{/if}>

<{if $prefix}>
<div class="xo-moduleadmin-infobox outer">
    <div class="xo-window">
        <div class="xo-window-title">
            <img src="<{$xoops_url}>/media/xoops/images/icons/16/info.png" alt="" />&nbsp;<{$smarty.const._AM_PROTECTOR_PREFIX_CHANGEDB}>
            <a class="down" href="javascript:;">&nbsp;</a>
        </div>
        <div class="xo-window-data">
            <ul>
                <li class="black"><{$prefix}></li>
                <li>&nbsp;</li>
                <li class="black"><{$prefix_line}></li>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
</div>
<{/if}>

<{if $protector_logger}>
<br />
<div class="xo-moduleadmin-infobox outer">
    <div class="xo-window">
        <div class="xo-window-title">
            <img src="<{$xoops_url}>/media/xoops/images/icons/16/info.png" alt="" />&nbsp;<{$smarty.const._AM_PROTECTOR_PREFIX_LOG}>
            <a class="down" href="javascript:;">&nbsp;</a>
        </div>
        <div class="xo-window-data">
            <ul>
                <li class="black"><{$protector_logger}></li>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
</div>
<{/if}>