<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<{$info_msg|default:''}>
<{$error_msg|default:''}>
<!--Banner-->
<{if $banner_count|default:false}>
<h4><{$smarty.const._AM_BANNERS_BANNERS_CURRENT}></h4>
<table id="xo-bannerslist-sorter" class="outer tablesorter">
    <thead>
    <tr>
        <th class="txtcenter"><{$smarty.const._AM_BANNERS_BANNERS_IMPRESSIONS}></th>
        <th class="txtcenter"><{$smarty.const._AM_BANNERS_BANNERS_IMPRESIONLEFT}></th>
        <th class="txtcenter width10"><{$smarty.const._AM_BANNERS_BANNERS_CLICKS}></th>
        <th class="txtcenter width10"><{$smarty.const._AM_BANNERS_BANNERS_NCLICKS}></th>
        <th class="txtcenter width15"><{$smarty.const._AM_BANNERS_CLIENTS_NAME}></th>
        <th class="txtcenter width10"><{$smarty.const._AM_BANNERS_ACTION}></th>
    </tr>
    </thead>
    <tbody>
    <{foreach item=banneritem from=$banner}>
    <tr class="<{cycle values='even,odd'}>">
        <td class="txtcenter"><{$banneritem.impmade}></td>
        <td class="txtcenter"><{$banneritem.left}></td>
        <td class="txtcenter"><{$banneritem.clicks}></td>
        <td class="txtcenter"><{$banneritem.percent}>%</td>
        <td class="txtcenter"><{$banneritem.name}></td>
        <td class="xo-actions txtcenter">
            <img onclick="display_dialog(<{$banneritem.bid}>, true, true, 'slide', 'slide', 200, 520);" src="<{xoAdminIcons 'display.png'}>" alt="<{$smarty.const._AM_BANNERS_VIEW}>" title="<{$smarty.const._AM_BANNERS_VIEW}>" />
            <a href="banners.php?op=edit&amp;bid=<{$banneritem.bid}>" title="<{$smarty.const._AM_BANNERS_EDIT}>">
                <img src="<{xoAdminIcons 'edit.png'}>" alt="<{$smarty.const._AM_BANNERS_EDIT}>" />
            </a>
            <a href="banners.php?op=delete&amp;bid=<{$banneritem.bid}>" title="<{$smarty.const._AM_BANNERS_DELETE}>">
                <img src="<{xoAdminIcons 'delete.png'}>" alt="<{$smarty.const._AM_BANNERS_DELETE}>" />
            </a>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>
<div class="clear spacer"></div>
<{if $nav_menu_banner|default:false}>
<div class="xo-avatar-pagenav floatright"><{$nav_menu_banner}></div><div class="clear spacer"></div>
<{/if}>
<!--Pop-pup-->
<{foreach item=banner from=$popup_banner}>
<div id="dialog<{$banner.bid}>" title="<{$banner.name}>" style='display:none;'>
    <{$banner.imageurl}>
</div>
<{/foreach}>
<!--Pop-pup-->
<{/if}>

<!--Banner Finish-->
<{if $banner_finish_count|default:false}>
<h4><{$smarty.const._AM_BANNERS_BANNERS_FINISH}></h4>
<table id="xo-bannersfinish-sorter" class="outer tablesorter">
    <thead>
    <tr>
        <th class="txtcenter"><{$smarty.const._AM_BANNERS_BANNERS_IMPRESSIONS}></th>
        <th class="txtcenter"><{$smarty.const._AM_BANNERS_BANNERS_STARTDATE}></th>
        <th class="txtcenter"><{$smarty.const._AM_BANNERS_BANNERS_ENDDATE}></th>
        <th class="txtcenter width10"><{$smarty.const._AM_BANNERS_BANNERS_CLICKS}></th>
        <th class="txtcenter width10"><{$smarty.const._AM_BANNERS_BANNERS_NCLICKS}></th>
        <th class="txtcenter width15"><{$smarty.const._AM_BANNERS_CLIENTS_NAME}></th>
        <th class="txtcenter width10"><{$smarty.const._AM_BANNERS_ACTION}></th>
    </tr>
    </thead>
    <tbody>
    <{foreach item=finished_banner from=$banner_finish}>
    <tr class="<{cycle values='even,odd'}>">
        <td class="txtcenter"><{$finished_banner.impressions}></td>
        <td class="txtcenter"><{$finished_banner.datestart}></td>
        <td class="txtcenter"><{$finished_banner.dateend}></td>
        <td class="txtcenter"><{$finished_banner.clicks}></td>
        <td class="txtcenter"><{$finished_banner.percent}>%</td>
        <td class="txtcenter"><{$finished_banner.name}></td>
        <td class="xo-actions txtcenter">
            <img onclick="display_dialog(<{$finished_banner.bid}>, true, true, 'slide', 'slide', 200, 520);" src="<{xoAdminIcons 'display.png'}>" alt="<{$smarty.const._AM_BANNERS_VIEW}>" title="<{$smarty.const._AM_BANNERS_VIEW}>" />
            <a href="banners.php?op=reload&amp;bid=<{$finished_banner.bid}>" title="<{$smarty.const._AM_BANNERS_BANNERS_RELOAD}>">
                <img src="<{xoAdminIcons 'reload.png'}>" alt="<{$smarty.const._AM_BANNERS_BANNERS_RELOAD}>"/>
            </a>
            <a href="banners.php?op=delete&amp;bid=<{$finished_banner.bid}>" title="<{$smarty.const._AM_BANNERS_DELETE}>">
                <img src="<{xoAdminIcons 'delete.png'}>" alt="<{$smarty.const._AM_BANNERS_DELETE}>" />
            </a>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>
<div class="clear spacer"></div>
<{if $nav_menu_bannerF|default:false}>
<{$nav_menu_bannerF}>
<div class="clear spacer"></div>
<{/if}>
<!--Pop-pup-->
<{foreach item=banner_finish from=$popup_banner_finish}>
<div id="dialog<{$banner_finish.bid}>" title="<{$banner_finish.name}>" style='display:none;'>
    <{$banner_finish.imageurl}>
</div>
<{/foreach}>
<!--Pop-pup-->
<{/if}>
<!-- Display form (add,edit) -->
<{$form|default:''}>