<{include file="admin:system/admin_breadcrumb.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>

<!--Preferences-->
<{if $menu|default:false}>
<div class="xo-catsetting">
    <{foreach item=preferenceitem from=$preferences}>
    <a class="xo-tooltip" href="admin.php?fct=preferences&amp;op=show&amp;confcat_id=<{$preferenceitem.id}>" title="<{$preferenceitem.name}>">
        <img src="<{$preferenceitem.image}>" alt="<{$preferenceitem.name}>" />
        <span><{$preferenceitem.name}></span>
    </a>
    <{/foreach}>
    <a class="xo-tooltip" href="admin.php?fct=preferences&amp;op=showmod&amp;mod=1" title="<{translate key='SYSTEM_PREFERENCES' dirname='system'}>">
        <img src="<{xoAdminIcons 'xoops/system_mods.png'}>" alt="<{translate key='SYSTEM_PREFERENCES' dirname='system'}>" />
        <span><{translate key='SYSTEM_PREFERENCES' dirname='system'}></span>
    </a>
</div>
<{/if}>
<div class="clear">&nbsp;</div>
<{$form}>