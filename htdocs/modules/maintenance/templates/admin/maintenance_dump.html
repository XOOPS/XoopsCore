<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<!-- Display form -->
<{$form|default:''}>

<{if $result_m|default:false}>
    <table class="outer tablesorter">
        <thead>
        <tr>
            <th class="txtleft"><{$smarty.const._AM_MAINTENANCE_DUMP_TABLES}></th>
            <th class="txtcenter width20"><{$smarty.const._AM_MAINTENANCE_DUMP_NB_RECORDS}></th>
            <th class="txtcenter width10"><{$smarty.const._AM_MAINTENANCE_DUMP_STRUCTURES}></th>
        </tr>
        </thead>
        <tbody>
            <{foreach item=result_module from=$result_m}>
            <tr class="<{cycle values='even,odd'}> alignmiddle">
                <td class="txtleft" colspan="3">
                    <span class="bold"><{$result_module.name}></span>
                </td>
            </tr>
            <{if $result_module.table}>
            <{foreach item=result_table from=$result_module.table}>
            <tr class="<{cycle values='even,odd'}> txtcenter">
                <td class="txtleft"><{$result_table.name}></td>
                <td><{$result_table.records}> <{$smarty.const._AM_MAINTENANCE_DUMP_RECORDS}></td>
                <td>
                    <{if $result_table.structure}>
                    <span class="ico ico-tick"></span>
                    <{else}>
                    <span class="ico ico-cross"></span>
                    <{/if}>
                </td>
            </tr>
            <{/foreach}>
            <{else}>
            <tr class="<{cycle values='even,odd'}> txtcenter">
                <td colspan="3"><{$smarty.const._AM_MAINTENANCE_DUMP_NO_TABLES}></td>
            </tr>
            <{/if}>
            <{/foreach}>
        </tbody>
    </table>
<{/if}>

<{if $result_t|default:false}>
    <table class="outer tablesorter">
        <thead>
        <tr>
            <th class="txtleft"><{$smarty.const._AM_MAINTENANCE_DUMP_TABLES}></th>
            <th class="txtcenter width20"><{$smarty.const._AM_MAINTENANCE_DUMP_NB_RECORDS}></th>
            <th class="txtcenter width10"><{$smarty.const._AM_MAINTENANCE_DUMP_STRUCTURES}></th>
        </tr>
        </thead>
        <tbody>
            <{foreach item=result_table from=$result_t}>
            <tr class="<{cycle values='even,odd'}> txtcenter">
                <td class="txtleft"><{$result_table.name}></td>
                <td><{$result_table.records}> <{$smarty.const._AM_MAINTENANCE_DUMP_RECORDS}></td>
                <td>
                    <{if $result_table.structure}>
                    <span class="ico ico-tick"></span>
                    <{else}>
                    <span class="ico ico-cross"></span>
                    <{/if}>
                </td>
            </tr>
            <{/foreach}>
        </tbody>
    </table>
<{/if}>

<{if $result_write|default:false}>
<div class="xo-moduleadmin-config outer">
    <div class="xo-window">
        <div class="xo-window-title">
            <span class="ico ico-flag-green"></span>&nbsp;<{$smarty.const._AM_MAINTENANCE_CENTER_RESULT}>
            <a class="down" href="javascript:;">&nbsp;</a>
        </div>
        <div class="xo-window-data">
        <ul>
            <{if $write}>
            <li class="green">
                <span class="ico ico-tick"></span>&nbsp;<{$smarty.const._AM_MAINTENANCE_DUMP_FILE_CREATED}>
                &nbsp;<a class="btn btn-success btn-mini" href="<{$xoops_url}>/modules/maintenance/dump/<{$file_name}>" target="_blank" ><i class="icon-download icon-white"></i> <{$smarty.const._AM_MAINTENANCE_DUMP_DOWNLOAD}></a>
            </li>
            <{else}>
            <li class="red">
                <span class="ico ico-cross"></span>&nbsp;<{$smarty.const._AM_MAINTENANCE_DUMP_FILE_NOTCREATED}>
            </li>
            <{/if}>
        </ul>
        </div>
    </div>
    <div class="clear"></div>
</div>
<{/if}>

<{if $files|default:false}>
<table class="outer tablesorter">
    <thead>
    <tr>
        <th class="txtleft"><{$smarty.const._AM_MAINTENANCE_DUMP_FILES}></th>
        <th class="txtcenter"><{$smarty.const._AM_MAINTENANCE_CENTER_SIZE}>&nbsp;<{$smarty.const._AM_MAINTENANCE_CENTER_SIZE_SUFFIX}></th>
        <th class="txtcenter"><{$smarty.const._AM_MAINTENANCE_ACTIONS}></th>
    </tr>
    </thead>
    <tbody>
    <{foreach item=file_item from=$file_arr}>
    <tr class="<{cycle values='even,odd'}> alignmiddle">
        <td class="txtleft">
            <{$file_item.name}>
        </td>
        <td class="txtcenter span1">
            <{$file_item.size}>
        </td>
        <td class="xo-actions txtcenter span2">
            <a href="<{$xoops_url}>/modules/maintenance/dump/<{$file_item.name}>" title="<{$smarty.const._AM_MAINTENANCE_DUMP_DOWNLOAD}>">
                <img src="<{xoModuleIcons16 'download.png'}>" alt="<{$smarty.const._AM_MAINTENANCE_DUMP_DOWNLOAD}>">
            </a>
            <a href="dump.php?op=dump_delete&amp;filename=<{$file_item.name}>" title="<{$smarty.const._AM_MAINTENANCE_DELETE}>">
                <img src="<{xoModuleIcons16 'delete.png'}>" alt="<{$smarty.const._AM_MAINTENANCE_DELETE}>">
            </a>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>
<div class="txtright">
    <a class="btn btn-danger btn" href="dump.php?op=dump_deleteall"><i class="icon-remove icon-white"></i> <{$smarty.const._AM_MAINTENANCE_DUMP_DELETEALL}></a>
</div>
<{/if}>