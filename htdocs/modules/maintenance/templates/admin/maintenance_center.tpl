<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<!-- Display form -->
<{$form|default:''}>

<{if $smarty_cache|default:false || $smarty_compile|default:false || $xoops_cache|default:false || $session|default:false || $maintenance|default:false}>
    <div class="xo-moduleadmin-config outer">
        <div class="xo-window">
            <div class="xo-window-title">
                <span class="ico ico-flag-green"></span>&nbsp;<{$smarty.const._AM_MAINTENANCE_CENTER_RESULT}>
                <a class="down" href="javascript:;">&nbsp;</a>
            </div>
            <div class="xo-window-data">
                <{if $smarty_cache || $smarty_compile || $xoops_cache || $session}>
                <ul>
                    <{if $smarty_cache}>
                    <li class="green">
                        <span class="ico ico-tick"></span>&nbsp;<{$result_smarty_cache}>
                    </li>
                    <{/if}>
                    <{if $smarty_compile}>
                    <li class="green">
                        <span class="ico ico-tick"></span>&nbsp;<{$result_smarty_compile}>
                    </li>
                    <{/if}>
                    <{if $xoops_cache}>
                    <li class="green">
                        <span class="ico ico-tick"></span>&nbsp;<{$result_xoops_cache}>
                    </li>
                    <{/if}>
                    <{if $session}>
                    <{if $result_session}>
                    <li class="green">
                        <span class="ico ico-tick"></span>&nbsp;<{$smarty.const._AM_MAINTENANCE_CENTER_RESULT_SESSION}>
                    </li>
                    <{else}>
                    <li class="red">
                        <span class="ico ico-cross"></span>&nbsp;<{$smarty.const._AM_MAINTENANCE_CENTER_RESULT_SESSION}>
                    </li>
                    <{/if}>
                    <{/if}>
                </ul>
                <{/if}>
                <{if $result_arr}>
                <table class="outer tablesorter">
                    <thead>
                    <tr>
                        <th class="txtleft"><{$smarty.const._AM_MAINTENANCE_CENTER_TABLES1}></th>
                        <th class="txtcenter"><{$smarty.const._AM_MAINTENANCE_CENTER_OPTIMIZE}></th>
                        <th class="txtcenter"><{$smarty.const._AM_MAINTENANCE_CENTER_CHECK}></th>
                        <th class="txtcenter"><{$smarty.const._AM_MAINTENANCE_CENTER_REPAIR}></th>
                        <th class="txtcenter"><{$smarty.const._AM_MAINTENANCE_CENTER_ANALYSE}></th>
                    </tr>
                    </thead>
                    <tbody>
                    <{foreach item=result from=$result_arr}>
                    <tr class="<{cycle values='even,odd'}> alignmiddle">
                        <td class="txtleft">
                            <{$result.table}>
                        </td>
                        <td class="txtcenter width10">
                            <{if $result.optimize}>
                            <span class="ico ico-tick"></span>
                            <{else}>
                            <span class="ico ico-cross"></span>
                            <{/if}>
                        </td>
                        <td class="txtcenter width10">
                            <{if $result.check}>
                            <span class="ico ico-tick"></span>
                            <{else}>
                            <span class="ico ico-cross"></span>
                            <{/if}>
                        </td>
                        <td class="xo-actions txtcenter width10">
                            <{if $result.repair}>
                            <span class="ico ico-tick"></span>
                            <{else}>
                            <span class="ico ico-cross"></span>
                            <{/if}>
                        </td>
                        <td class="xo-actions txtcenter width10">
                            <{if $result.analyse}>
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
            </div>
        </div>
        <div class="clear"></div>
    </div>
<{/if}>