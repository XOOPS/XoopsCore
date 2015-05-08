<ul class="breadcrumb">
    <{foreach item=breadcrumb from=$xo_sys_breadcrumb}>
        <{if $breadcrumb.home}>
            <li><a class="xo-tooltip" href="<{$breadcrumb.link}>" title="<{$breadcrumb.title}>"><span class="ico-monitor"></span></a>&nbsp;<span class="divider">/</span></li>
        <{else}>
            <{if $breadcrumb.link}>
            <li><a class="xo-tooltip" href="<{$breadcrumb.link}>" title="<{$breadcrumb.title}>"><{$breadcrumb.title}></a>&nbsp;<span class="divider">/</span></li>
            <{else}>
            <li class="active"><{$breadcrumb.title}></li>
            <{/if}>
        <{/if}>
    <{/foreach}>
</ul>
<{if $xo_sys_tips|default:false}>
<div class="tips ui-corner-all">
    <img class="floatleft xo-tooltip" src="<{xoAdminIcons 'tips.png'}>" alt="<{translate key='TIPS'}>" title="<{translate key='TIPS'}>" />
    <div class="floatleft"><{$xo_sys_tips}> - to change</div>
    <div class="clear">&nbsp;</div>
</div>
<{/if}>