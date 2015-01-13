<{include file="admin:system/admin_breadcrumb.tpl"}>
<{if $module}>
<div class="xo-module-logger outer">
    <div class="xo-window">
        <div class="xo-window-title">
            <span class="ico-application-osx-terminal xo-tooltip" title="<{$title}>"></span>&nbsp;<{$title}>
        </div>
        <div class="xo-window-data">
            <div class="xo-module-head">
                <div class="module_image">
                    <img src="<{$xoops_url}>/modules/<{$module->getVar('dirname')}>/<{$module->getInfo('image')}>" alt="" />
                </div>
                <div class="module_data">
                    <div class="name"><a href="<{$xoops_url}>/modules/<{$module->getInfo('dirname')}>/<{$module->getInfo('adminindex')}>"><{$module->getVar('name')}></a></div>
                    <div class="data">
                        <div><span class="bold"><{translate key='C_VERSION'}></span>&nbsp;<{$module->getInfo('version')}></div>
                        <div><span class="bold"><{translate key='C_AUTHOR'}></span>&nbsp;<{$module->getInfo('author')}></div>
                        <div><span class="bold"><{translate key='C_WEBSITE'}></span>&nbsp;<a href="<{$module->getInfo('module_website_url')}>" rel="external"><{$module->getInfo('module_website_name')}></a></div>
                        <div><span class="bold"><{translate key='C_LICENSE'}></span>&nbsp;<{$module->getInfo('license')}></div>
                    </div>
                    <div class="floatright">
                        <a class="btn btn-primary xo-tooltip" href="<{$from_link}>" title="<{$from_title}>">
                            <span class="ico-brick-go"></span>
                            <{$from_title}>
                        </a><{if $install|default:false}>
                        <{if $module->getInfo('has_admin')}>
                        <a class="btn btn-success xo-tooltip" href="<{$xoops_url}>/modules/<{$module->getVar('dirname')}>/<{$module->getInfo('adminindex')}>" title="<{translate key='MANAGE_MODULE' dirname='system'}>">
                            <span class="ico-package"></span>
                            <{translate key='MANAGE_MODULE' dirname='system'}>
                        </a>
                        <{/if}>
                        <{if is_array($module->getInfo('blocks'))}>
                        <a class="btn btn-info xo-tooltip" href="admin.php?fct=blocksadmin&amp;selgen=<{$module->getVar('mid')}>" title="<{translate key='MANAGE_BLOCKS' dirname='system'}>">
                            <span class="ico-bricks"></span>
                            <{translate key='MANAGE_BLOCKS' dirname='system'}>
                        </a>
                        <{/if}>
                        <{if is_array($module->getInfo('config'))}>
                        <a class="btn btn-info xo-tooltip" href="admin.php?fct=preferences&amp;op=showmod&amp;mod=<{$module->getVar('mid')}>" title="<{translate key='MANAGE_BLOCKS' dirname='system'}>">
                            <span class="ico-cog"></span>
                            <{translate key='MANAGE_PREFERENCES' dirname='system'}>
                        </a>
                        <{/if}>
                        <{/if}>
                    </div>
                    <br class="clear" />
                </div>
            </div>
            <div class="well xo-module-log">
                <ul>
                    <{foreach item=top from=$log}>
                    <{if !is_array($top)}>
                    <li class="xo-step"><{$top}></li>
                    <{else}>
                        <ul>
                            <{foreach item=child from=$top}>
                            <li class="xo-child"><{$child}></li>
                            <{/foreach}>
                        </ul>
                    <{/if}>
                    <{/foreach}>
                </ul>
            </div>
            <div class="floatright">
                <a class="btn btn-primary xo-tooltip" href="<{$from_link}>" title="<{$from_title}>">
                    <span class="ico-brick-go"></span>
                    <{$from_title}>
                </a>
                <{if $install|default:false}>
                <{if $module->getInfo('has_admin')}>
                <a class="btn btn-success xo-tooltip" href="<{$xoops_url}>/modules/<{$module->getVar('dirname')}>/<{$module->getInfo('adminindex')}>" title="<{translate key='MANAGE_MODULE' dirname='system'}>">
                    <span class="ico-package"></span>
                    <{translate key='MANAGE_MODULE' dirname='system'}>
                </a>
                <{/if}>
                <{if is_array($module->getInfo('blocks'))}>
                <a class="btn btn-info xo-tooltip" href="admin.php?fct=blocksadmin&amp;selgen=<{$module->getVar('mid')}>" title="<{translate key='MANAGE_BLOCKS' dirname='system'}>">
                    <span class="ico-bricks"></span>
                    <{translate key='MANAGE_BLOCKS' dirname='system'}>
                </a>
                <{/if}>
                <{if is_array($module->getInfo('config'))}>
                <a class="btn btn-info xo-tooltip" href="admin.php?fct=preferences&amp;op=showmod&amp;mod=<{$module->getVar('mid')}>" title="<{translate key='MANAGE_BLOCKS' dirname='system'}>">
                    <span class="ico-cog"></span>
                    <{translate key='MANAGE_PREFERENCES' dirname='system'}>
                </a>
                <{/if}>
                <{/if}>
            </div>
            <br class="clear" />
        </div>
    </div>
    <div class="clear"></div>
</div>
<{/if}>