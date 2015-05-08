<div class="xo-moduleadmin-icon outer">
    <div class="xo-window">
        <div class="xo-window-title"><span class="ico ico-lightning"></span>&nbsp;<{translate key='LINKS'}></div>
        <div class="xo-window-data">
            <div class="moduleadmin-icon">
                <{foreach item=menu from=$xo_admin_index_menu}>
                <a class="xo-tooltip" href="../<{$menu.link}>" title="<{$menu.title}>">
                    <img src="<{$menu.icon}>" alt="<{$menu.title}>" />
                    <span><{$menu.title}></span>
                </a>
                <{/foreach}>
            </div>
        </div>
    </div>
</div>
<{include file="admin:system/admin_infobox.tpl" class="xo-moduleadmin-box"}>
<div class="clear"></div>
<div class="xo-moduleadmin-config outer">
    <div class="xo-window">
        <div class="xo-window-title">
            <span class="ico ico-computer"></span>&nbsp;<{translate key="CONFIGURATION_CHECK"}>
            <a class="down" href="javascript:;">&nbsp;</a>
        </div>
        <div class="xo-window-data">
            <ul>
            <{foreach item=config from=$xo_admin_index_config}>
                <{if $config.type == 'error'}>
                <li class="red">
                    <span class="ico ico-cross"></span>&nbsp;<{$config.text}>
                </li>
                <{elseif $config.type == 'warning'}>
                <li class="orange">
                    <span class="ico ico-warning"></span>&nbsp;<{$config.text}>
                </li>
                <{else}>
                <li class="green">
                    <span class="ico ico-tick"></span>&nbsp;<{$config.text}>
                </li>
                <{/if}>
            <{/foreach}>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
</div>