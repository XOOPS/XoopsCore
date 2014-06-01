<{$error_msg}>
<{if $banner_count == true}>
<h4><{$smarty.const._AM_BANNERS_BANNERS_CURRENT}></h4>
<table id="xo-bannerslist-sorter" class="table table-striped table-bordered table-condensed">
    <thead>
    <tr>
        <th class="width5" style="text-align: center"><{$smarty.const._MD_BANNERS_INDEX_ID}></th>
        <th class="width15" style="text-align: center"><{$smarty.const._AM_BANNERS_CLIENTS_NAME}></th>
        <th style="text-align: center"><{$smarty.const._AM_BANNERS_BANNERS_IMPRESSIONS}></th>
        <th style="text-align: center"><{$smarty.const._AM_BANNERS_BANNERS_IMPRESIONLEFT}></th>
        <th class="width10" style="text-align: center"><{$smarty.const._AM_BANNERS_BANNERS_CLICKS}></th>
        <th class="width10" style="text-align: center"><{$smarty.const._AM_BANNERS_BANNERS_NCLICKS}></th>
        <th class="width15" style="text-align: center"><{$smarty.const._AM_BANNERS_ACTION}></th>
    </tr>
    </thead>
    <tbody>
    <{foreach item=banner from=$banner}>
    <tr class="<{cycle values='even,odd'}>">
        <td style="text-align: center">
            <a class="xo-tooltip" href="<{$banner.clickurl}>" title="<{$banner.clickurl}>" rel='external'><{$banner.bid}></a>
        </td>
        <td style="text-align: center"><{$banner.name}></td>
        <td style="text-align: center"><{$banner.impmade}></td>
        <td style="text-align: center"><{$banner.left}></td>
        <td style="text-align: center"><{$banner.clicks}></td>
        <td style="text-align: center"><{$banner.percent}>%</td>
        <td class="xo-actions" style="text-align: center">
            <img class="xo-tooltip" onclick="display_dialog(<{$banner.bid}>, true, true, 'slide', 'slide', 200, 520);" src="<{xoAdminIcons display.png}>" alt="<{$smarty.const._AM_BANNERS_VIEW}>" title="<{$smarty.const._AM_BANNERS_VIEW}>" />
            <a class="xo-tooltip" href="index.php?op=edit&amp;bid=<{$banner.bid}>" title="<{$smarty.const._AM_BANNERS_EDIT}>">
                <img src="<{xoAdminIcons edit.png}>" alt="<{$smarty.const._AM_BANNERS_EDIT}>" />
            </a>
            <{if $banner.uid != 0}>
            <a class="xo-tooltip" href="index.php?op=EmailStats&amp;bid=<{$banner.bid}>" title="<{$smarty.const._MD_BANNERS_INDEX_EMAIL}>">
                <img src="<{xoAdminIcons mail_send.png}>" alt="<{$smarty.const._MD_BANNERS_INDEX_EMAIL}>" />
            </a>
            <{/if}>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>
<div class="clear spacer"></div>
<{if $nav_menu_banner}>
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
<{if $banner_finish_count == true}>
<h4><{$smarty.const._AM_BANNERS_BANNERS_FINISH}></h4>

<table id="xo-bannerslist-sorter" class="table table-striped table-bordered table-condensed">
    <thead>
    <tr>
        <th class="width5" style="text-align: center"><{$smarty.const._MD_BANNERS_INDEX_ID}></th>
        <th class="width15" style="text-align: center"><{$smarty.const._AM_BANNERS_CLIENTS_NAME}></th>
        <th style="text-align: center"><{$smarty.const._AM_BANNERS_BANNERS_IMPRESSIONS}></th>
        <th style="text-align: center"><{$smarty.const._AM_BANNERS_BANNERS_STARTDATE}></th>
        <th style="text-align: center"><{$smarty.const._AM_BANNERS_BANNERS_ENDDATE}></th>
        <th class="width10" style="text-align: center"><{$smarty.const._AM_BANNERS_BANNERS_CLICKS}></th>
        <th class="width10" style="text-align: center"><{$smarty.const._AM_BANNERS_BANNERS_NCLICKS}></th>
        <th class="width15" style="text-align: center"><{$smarty.const._AM_BANNERS_ACTION}></th>
    </tr>
    </thead>
    <tbody>
    <{foreach item=banner_finish from=$banner_finish}>
    <tr class="<{cycle values='even,odd'}>">
        <td style="text-align: center">
            <a class="xo-tooltip" href="<{$banner_finish.clickurl}>" title="<{$banner_finish.clickurl}>" rel='external'><{$banner_finish.bid}></a>
        </td>
        <td style="text-align: center"><{$banner_finish.name}></td>
        <td style="text-align: center"><{$banner_finish.impressions}></td>
        <td style="text-align: center"><{$banner_finish.datestart}></td>
        <td style="text-align: center"><{$banner_finish.dateend}></td>
        <td style="text-align: center"><{$banner_finish.clicks}></td>
        <td style="text-align: center"><{$banner_finish.percent}>%</td>
        <td class="xo-actions" style="text-align: center">
            <img class="xo-tooltip" onclick="display_dialog(<{$banner_finish.bid}>, true, true, 'slide', 'slide', 200, 520);" src="<{xoAdminIcons display.png}>" alt="<{$smarty.const._AM_BANNERS_VIEW}>" title="<{$smarty.const._AM_BANNERS_VIEW}>" />
            <a class="xo-tooltip" href="index.php?op=edit&amp;bid=<{$banner_finish.bid}>" title="<{$smarty.const._AM_BANNERS_EDIT}>">
                <img src="<{xoAdminIcons edit.png}>" alt="<{$smarty.const._AM_BANNERS_EDIT}>" />
            </a>
            <{if $banner_finish.uid != 0}>
            <a class="xo-tooltip" href="index.php?op=EmailStats&amp;bid=<{$banner_finish.bid}>" title="<{$smarty.const._MD_BANNERS_INDEX_EMAIL}>">
                <img src="<{xoAdminIcons mail_send.png}>" alt="<{$smarty.const._MD_BANNERS_INDEX_EMAIL}>" />
            </a>
            <{/if}>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>
<div class="clear spacer"></div>
<{if $nav_menu_bannerF}>
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
<!-- Display form (edit) -->
<{$form}>