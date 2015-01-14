<{include file="admin:system/admin_breadcrumb.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<div class="btn-group pull-left">
    <a class="btn xo-tooltip card-view <{if $view_line == 'hide'}>disabled<{/if}>" href="#" title="<{translate key='LARGE_VIEW' dirname='system'}>"><b class="ico-application-view-tile"></b></a>
    <a class="btn xo-tooltip table-view <{if $view_large == 'hide'}>disabled<{/if}>" href="#" title="<{translate key='LINE_VIEW' dirname='system'}>"><b class="ico-application-view-list"></b></a>
</div>
<div class="pull-right">
    <a class="btn btn-primary xo-tooltip" href="admin.php?fct=blocksadmin&amp;op=edit&amp;bid=5" title="<{translate key='MANAGE_MENU' dirname='system'}>">
        <span class="ico-layout-sidebar"></span>
        <{translate key='MANAGE_MENU' dirname='system'}>
    </a>
</div>
<div class="clear spacer"></div>
<div class="xo-module-installed outer">
    <div class="xo-window xo-module-list">
        <div class="xo-window-title">
            <span class="ico-brick xo-tooltip" title="<{translate key='INSTALLED_MODULES' dirname='system'}>"></span>&nbsp;<{translate key='INSTALLED_MODULES' dirname='system'}>
        </div>
        <div class="xo-window-data">
            <div class="xo-content-card <{$view_large}>"><{include file="admin:system/system_modules_card.tpl"}></div>
            <div class="xo-content-table <{$view_line}>"><{include file="admin:system/system_modules_table.tpl"}></div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="xo-module-install outer">
    <div class="xo-window xo-module-list">
        <div class="xo-window-title">
            <span class="ico-box xo-tooltip" title="<{translate key='AVAILABLE_MODULES' dirname='system'}>"></span>&nbsp;<{translate key='AVAILABLE_MODULES' dirname='system'}>
        </div>
        <div class="xo-window-data">
            <{include file="admin:system/system_modules_available.tpl"}>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>

<!-- Modules messages definition -->
<div class="modal-backdrop hide"></div>
<div id="install" class="modal hide">
    <div class="modal-header">
        <a class="close" href="#" onclick="$('.modal-backdrop').click();">x</a>
        <h3><span class="ico-application-go"></span>&nbsp;<{translate key='A_INSTALL'}></h3>
    </div>
    <div class="modal-body">
        <p class="modal-data"></p>
    </div>
    <div class="modal-footer">
        <form id="install-form" method="post" action="admin.php">
            <a class="btn" href="javascript:" onclick="$('.modal-backdrop').click();">
                <span class="ico-cross"></span>
                <{translate key='A_CANCEL'}>
            </a>
            <a class="btn btn-primary" href="javascript:;" onclick="$('#install-form').submit()">
                <span class="ico-application-go"></span>
                <{translate key='A_INSTALL'}>
            </a>
            <{securityToken}>
            <input type="hidden" name="fct" value="modulesadmin" />
            <input type="hidden" name="op" value="install" />
            <input id="install-dir" type="hidden" name="dirname" value="" />
        </form>
    </div>
</div>

<div id="update" class="modal hide">
    <div class="modal-header">
        <a class="close" href="#" onclick="$('.modal-backdrop').click();">x</a>
        <h3><span class="ico-arrow-refresh-small"></span>&nbsp;<{translate key='A_UPDATE'}></h3>
    </div>
    <div class="modal-body">
        <p class="modal-data"></p>
    </div>
    <div class="modal-footer">
        <form id="update-form" method="post" action="admin.php">
            <a class="btn" href="javascript:" onclick="$('.modal-backdrop').click();">
                <span class="ico-cross"></span>
                <{translate key='A_CANCEL'}>
            </a>
            <a class="btn btn-primary" href="javascript:;" onclick="$('#update-form').submit()">
                <span class="ico-arrow-refresh-small"></span>
                <{translate key='A_UPDATE'}>
            </a>
            <{securityToken}>
            <input type="hidden" name="fct" value="modulesadmin" />
            <input type="hidden" name="op" value="update" />
            <input id="update-id" type="hidden" name="mid" value="" />
        </form>
    </div>
</div>

<div id="uninstall" class="modal hide">
    <div class="modal-header">
        <a class="close" href="#" onclick="$('.modal-backdrop').click();">x</a>
        <h3><span class="ico-application-delete"></span>&nbsp;<{translate key='A_UNINSTALL'}></h3>
    </div>
    <div class="modal-body">
        <p class="modal-data"></p>
    </div>
    <div class="modal-footer">
        <form id="delete-form" method="post" action="admin.php">
            <a class="btn" href="javascript:" onclick="$('.modal-backdrop').click();">
                <span class="ico-cross"></span>
                <{translate key='A_CANCEL'}>
            </a>
            <a class="btn btn-danger" href="javascript:;" onclick="$('#delete-form').submit()">
                <span class="ico-application-delete"></span>
                <{translate key='A_UNINSTALL'}>
            </a>
            <{securityToken}>
            <input type="hidden" name="fct" value="modulesadmin" />
            <input type="hidden" name="op" value="uninstall" />
            <input id="uninstall-id" type="hidden" name="mid" value="" />
        </form>
    </div>
</div>