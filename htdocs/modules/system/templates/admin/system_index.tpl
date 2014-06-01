<{includeq file="admin:system|admin_breadcrumb.tpl"}>
<{includeq file="admin:system|admin_tips.tpl"}>
<{includeq file="admin:system|admin_buttons.tpl"}>
<script type="text/javascript">
    IMG_ON = '<{xoAdminIcons success.png}>';
    IMG_OFF = '<{xoAdminIcons cancel.png}>';
</script>
<table class="outer">
    <thead>
        <tr>
            <th class="txtcenter"><{translate key='SECTION'}></th>
            <th class="txtcenter"><{translate key='DESCRIPTION'}></th>
            <th class="txtcenter">&nbsp;</th>
            <th class="txtcenter">&nbsp;</th>
        </tr>
    </thead>

    <tbody>
    <{foreach item=menu from=$menu}>
    <tr class="<{cycle values='even,odd'}>">
        <td class="bold width15">
            <a class="xo-tooltip" href="admin.php?fct=<{$menu.file}>" title="<{translate key='GO_TO'}>: <{$menu.title}>">
                <img class="xo-imgmini" src='<{$theme_icons}>/<{$menu.icon}>' alt="<{$menu.title}>" />
                <{$menu.title}>
            </a>
        </td>
        <td class=""><{$menu.desc}></td>
        <td class="width15"><{$menu.infos}></td>
        <td class="xo-actions width2">
            <{if $menu.used}>
                <img id="loading_<{$menu.file}>" src="assets/images/spinner.gif" style="display:none;" alt="<{translate key='LOADING'}>" />
                <img class="xo-tooltip" id="<{$menu.file}>" onclick="system_setStatus( { op: 'system_activate', type: '<{$menu.file}>' }, '<{$menu.file}>', 'admin.php' )" src="<{if $menu.status}><{xoAdminIcons success.png}><{else}><{xoAdminIcons cancel.png}><{/if}>" alt="<{translate key='CHANGE_STATUS'}>" title="<{translate key='CHANGE_STATUS'}>" />
            <{/if}>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>
