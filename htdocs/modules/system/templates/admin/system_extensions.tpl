<{include file="admin:system/admin_breadcrumb.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<table class="outer">
    <thead>
    <tr>
        <th>#</th>
        <th><{translate key='NAME'}></th>
        <th><{translate key='VERSION'}></th>
        <th><{translate key='DETAILS'}></th>
        <th><{translate key='ACTIONS'}></th>
    </tr>
    </thead>
    <tbody>
    <{foreach item=extension from=$extension_list}>
    <tr>
        <td class="span1">
            <{if $extension->getInfo('install')}>
            <a href="<{$xoops_url}>/modules/<{$extension->getInfo('dirname')}>/<{$extension->getInfo('adminindex')}>">
                <img class="xo-tooltip" src="<{$extension->getInfo('logo_large')}>"
                     alt="<{$extension->getInfo('name')}>" title="<{$extension->getInfo('name')}>"/>
            </a>
            <{else}>
            <img class="xo-tooltip" src="<{$extension->getInfo('logo_large')}>" alt="<{$extension->getInfo('name')}>"
                 title="<{$extension->getInfo('name')}>"/>
            <{/if}>
        </td>
        <td class="span3">
            <{if $extension->getInfo('install')}>
            <a href="<{$xoops_url}>/modules/<{$extension->getInfo('dirname')}>/<{$extension->getInfo('adminindex')}>">
                <strong><{$extension->getInfo('name')}></strong>
            </a>
            <{else}>
            <{$extension->getInfo('name')}>
            <{/if}>
        </td>
        <td class="span2">
            <{$extension->getInfo('version')}>
        </td>
        <td class="span4">
            <ul class="xo-extension-detail">
                <li><span class="bold"><{translate key='C_AUTHOR'}></span>&nbsp;<{$extension->getInfo('author')}><{if $extension->getInfo('nickname')}> - (<{$extension->getInfo('nickname')}>)<{/if}>
                </li>
                <li class="hide detail-<{$extension->getInfo(dirname)}>"><span class="bold"><{translate key='C_LICENSE'}></span>&nbsp;<{$extension->getInfo('license')}>
                </li>
                <li class="hide detail-<{$extension->getInfo(dirname)}>"><span class="bold"><{translate key='C_DESCRIPTION'}></span>&nbsp;<{$extension->getInfo(description)}>
                </li>
            </ul>
        </td>
        <td class="span2">
            <a class="xo-tooltip" href="javascript:"
               onclick="$('li.detail-<{$extension->getInfo(dirname)}>').toggle('slow');"
               title="<{translate key='DETAILS'}>"><span class="ico-magnifier"></span></a>
            <{if $extension->getInfo('install')}>
            <a class="xo-tooltip" href="javascript:" onclick="module_Update(<{$extension->getInfo(mid)}>)"
               title="<{translate key='A_UPDATE'}>"><span class="ico-arrow-refresh-small"></span></a>

            <{if $extension->getInfo('hasconfig')}>
                <a class="xo-tooltip" href="admin.php?fct=preferences&mod=<{$extension->getInfo('mid')}>" title="<{translate key='PREFERENCES'}>">
                    <span class="ico-application-form-edit"></span></a>
            <{/if}>

            <a class="xo-tooltip" href="javascript:" onclick="module_Uninstall(<{$extension->getInfo(mid)}>)"
               title="<{translate key='A_UNINSTALL'}>"><span
                    class="ico-application-delete"></span></a>
            <{else}>
            <a class="xo-tooltip" href="javascript:" onclick="module_Install('<{$extension->getInfo(dirname)}>')"
               title="<{translate key='A_INSTALL'}> <{$extension->getInfo(name)}>"><span
                    class="ico-application-go"></span></a>
            <{/if}>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>
<{foreach item=extension from=$extension_list}>
<div id="data-<{$extension->getInfo('mid')}>" class="hide">
    <div class="module_card">
        <div class="module_image">
            <a class="rounded xo-tooltip"
               style="background: url('<{$xoops_url}>/modules/<{$extension->getInfo(dirname)}>/<{$extension->getInfo(image)}>')  no-repeat scroll center center transparent;"
               title="<{$extension->getVar('name')}>">
                <span class="rounded">&nbsp;</span>
            </a>
        </div>
        <div class="module_data">
            <div class="name"><{$extension->getInfo('name')}></div>
            <div class="data">
                <div><span class="bold"><{translate key='C_VERSION'}></span>
                    <{$extension->getInfo('version')}>
                </div>
                <div><span class="bold"><{translate key='C_LAST_UPDATE'}></span>
                    <{$extension->getInfo('update')}>
                </div>
            </div>
        </div>
        <div class="module_detail">
            <{$extension->getInfo('description')}>
        </div>
    </div>
</div>
<div id="data-<{$extension->getInfo(dirname)}>" class="hide">
    <div class="module_card">
        <div class="module_image">
            <a class="rounded xo-tooltip"
               style="background: url('<{$xoops_url}>/modules/<{$extension->getInfo(dirname)}>/<{$extension->getInfo(image)}>')  no-repeat scroll center center transparent;"
               title="<{$extension->getInfo(name)}>">
                <span class="rounded">&nbsp;</span>
            </a>
        </div>
        <div class="module_data">
            <div class="name"><{$extension->getInfo(name)}></div>
            <div class="data">
                <div><span class="bold"><{translate key='C_VERSION'}></span>&nbsp;<{$extension->getInfo(version)}>
                </div>
                <div><span class="bold"><{translate key='C_AUTHOR'}></span>&nbsp;<{$extension->getInfo(author)}>
                </div>
                <div><span class="bold"><{translate key='C_LICENSE'}></span>&nbsp;<{$extension->getInfo(license)}>
                </div>
            </div>
        </div>
        <div class="module_detail">
            <{$extension->getInfo(description)}>
        </div>
    </div>
</div>
<{/foreach}>

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
            <input type="hidden" name="fct" value="extensions"/>
            <input type="hidden" name="op" value="install"/>
            <input id="install-dir" type="hidden" name="dirname" value=""/>
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
            <input type="hidden" name="fct" value="extensions"/>
            <input type="hidden" name="op" value="update"/>
            <input id="update-id" type="hidden" name="mid" value=""/>
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
            <input type="hidden" name="fct" value="extensions"/>
            <input type="hidden" name="op" value="uninstall"/>
            <input id="uninstall-id" type="hidden" name="mid" value=""/>
        </form>
    </div>
</div>