<div class=" outer" style="width: 30%;">
    <div class="xo-window">
        <div class="xo-window-title"><{translate key='MENU_BLOCK' dirname='system'}></div>
        <div class="xo-window-data">
            <ul id="xo-module-sort">
            <{foreach item=module from=$modules_list}>
                <{if $module->getInfo('hasMain')}>
                <li id="mod_<{$module->getVar('mid')}>">
                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span><{$module->getVar('name')}>
                    <a id="menu-hide-<{$module->getVar('mid')}>" class="floatright" href="javascript:" onclick="module_Hide(<{$module->getVar('mid')}>)">
                        <span class="cursorpointer <{if $module->getVar('weight') == 0 }>ico-cross<{else}>ico-tick<{/if}>"></span>
                    </a>
                </li>
                <{/if}>
            <{/foreach}>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
</div>
