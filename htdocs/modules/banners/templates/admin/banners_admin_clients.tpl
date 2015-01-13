<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<br />
<{$info_msg|default:''}>
<{$error_msg|default:''}>
<{if $client_count|default:false}>
<table id="xo-bannersclient-sorter" cellspacing="1" class="outer tablesorter">
    <thead>
    <tr>
        <th class="txtcenter width15"><{$smarty.const._AM_BANNERS_CLIENTS_NAME}></th>
        <th class="txtcenter width15"><{$smarty.const._AM_BANNERS_CLIENTS_UNAME}></th>
        <th class="txtcenter width10"><{$smarty.const._AM_BANNERS_CLIENTS_ACTIVEBANNERS}></th>
        <th class="txtcenter"><{$smarty.const._AM_BANNERS_CLIENTS_MAIL}></th>
        <th class="txtcenter width10"><{$smarty.const._AM_BANNERS_ACTION}></th>
    </tr>
    </thead>
    <tbody>
    <{foreach item=clientitem from=$client}>
    <tr class="<{cycle values='even,odd'}>">
        <td class="txtcenter"><{$clientitem.name}></td>
        <{if $clientitem.uid == 0}>
        <td class="txtcenter"><{$clientitem.uname}></td>
        <{else}>
        <td class="txtcenter"><a title="<{$clientitem.uname}>" href="<{$xoops_url}>/userinfo.php?uid=<{$clientitem.uid}>" ><{$clientitem.uname}></a></td>
        <{/if}>
        <td class="txtcenter"><{$clientitem.banner_active}></td>
        <td class="txtcenter"><{$clientitem.email}></td>
        <td class="xo-actions txtcenter">
            <img onclick="display_dialog(<{$clientitem.cid}>, true, true, 'slide', 'slide', 325, 400);" src="<{xoAdminIcons 'display.png'}>" alt="<{$smarty.const._AM_BANNERS_VIEW}>" title="<{$smarty.const._AM_BANNERS_VIEW}>" />
            <a href="clients.php?op=edit&amp;cid=<{$clientitem.cid}>" title="<{$smarty.const._AM_BANNERS_EDIT}>">
                <img src="<{xoAdminIcons 'edit.png'}>" alt="<{$smarty.const._AM_BANNERS_EDIT}>" />
            </a>
            <a href="clients.php?op=delete&amp;cid=<{$clientitem.cid}>" title="<{$smarty.const._AM_BANNERS_DELETE}>">
                <img src="<{xoAdminIcons 'delete.png'}>" alt="<{$smarty.const._AM_BANNERS_DELETE}>" />
            </a>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>
<div class="clear spacer"></div>
<{if $nav_menu|default:false}>
<div class="xo-avatar-pagenav floatright"><{$nav_menu}></div>
<div class="clear spacer"></div>
<{/if}>
<!--Pop-pup-->
<{foreach item=client from=$client_banner}>
<div id="dialog<{$client.cid}>" title="<{$client.name}>" style='display:none;'>
    <table>
        <{if $client.uid != 0}>
        <tr>
            <td class="txtcenter">
                <img src="<{$client.avatar}>" alt="<{$client.uname}>" title="<{$client.uname}>" />
            </td>
            <td class="txtcenter">
                <a href='mailto:<{$client.email}>'><img src="<{xoAdminIcons 'mail_send.png'}>" alt="" title="" /></a>
                <a href='javascript:openWithSelfMain("<{$xoops_url}>/pmlite.php?send2=1&amp;to_userid=<{$client.uid}>","pmlite",450,370);'><img src="<{xoAdminIcons 'pm.png'}>" alt="" title="" /></a>
                <a href='<{$client.url}>' rel='external'><img src="<{xoAdminIcons 'url.png'}>" alt="" title="" ></a>
            </td>
        </tr>
        <{/if}>
        <tr>
            <td colspan="2">
                <ul style="padding: 8px;">
                    <li><span class="bold"><{$smarty.const._AM_BANNERS_CLIENTS_NAME}></span>&nbsp;:&nbsp;<{$client.name}></li>
                    <li><span class="bold"><{$smarty.const._AM_BANNERS_CLIENTS_UNAME}></span>&nbsp;:&nbsp;<{$client.uname}></li>
                    <li><span class="bold"><{$smarty.const._AM_BANNERS_CLIENTS_ACTIVEBANNERS}></span>&nbsp;:&nbsp;<{$client.banner_active}></li>
                    <li><span class="bold"><{$smarty.const._AM_BANNERS_CLIENTS_MAIL}></span>&nbsp;:&nbsp;<{$client.email}></li>
                    <li><span class="bold"><{$smarty.const._AM_BANNERS_CLIENTS_EXTRAINFO}></span>&nbsp;:&nbsp;<{$client.extrainfo}></li>
                </ul>
            </td>
        </tr>
    </table>
</div>
<{/foreach}>
<!--Pop-pup-->
<{/if}>
<!-- Display form (add,edit) -->
<{$form|default:''}>
