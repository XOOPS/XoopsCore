<div class="dashboard-content">
    <{if !$xoops_contents}>
    <section id="panel">
        <div class="page-header">
            <h1><{translateTheme key='DASHBOARD'}>&nbsp;
                <small><{translateTheme key='CONTROL_AND_MANAGE_YOUR_SITE'}></small>
            </h1>
        </div>
        <{if $error_msg}>
        <div class="row">
        <!-- <div class="col-md-2"></div> -->
        <div class="col-md-10 col-md-offset-1 alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <{foreach item=msg from=$error_msg}>
                <div class="alert-message error">
                    <p><{$msg}></p>
                </div>
            <{/foreach}>
        </div>
        </div>
        <{/if}>

        <div class="card-icons outer">
            <div class="xo-window">
                <div class="xo-window-title">
                    <span class="ico ico-wrench-5"></span>&nbsp;<{translate key='CONTROL_PANEL' dirname='system'}>
                </div>
                <div class="xo-window-data">
                    <h4 class="cp-cat"><{translateTheme key='SYSTEM_TOOLS'}></h4>
                    <div class="cp-icon">
                        <{foreach item=op from=$mod_options}>
                        <a class="xo-tooltip" href="<{$op.link}>" title="<{$op.desc}>">
                            <img src='<{$op.icon|default:"$theme_icons/icon_options.png"}>' alt="<{$op.desc}>"/>
                            <span><{$op.title}></span>
                        </a>
                        <{/foreach}>
                        <a class="xo-tooltip" href="<{xoAppUrl 'modules/system/admin.php'}>" title="<{translate key='SYSTEM_CONFIGURATION' dirname ='system'}>">
                            <img src='<{"$theme_icons/configuration.png"}>' alt="<{translate key='SYSTEM_CONFIGURATION' dirname ='system'}>"/>
                            <span><{translate key='SYSTEM_CONFIGURATION' dirname ='system'}></span>
                        </a>
                        <a class="xo-tooltip" href="<{xoAppUrl 'modules/system/help.php'}>"
                           title="<{translate key='HELP'}>">
                            <img src='<{"$theme_icons/help.png"}>' alt="<{translate key='HELP'}>"/>
                            <span><{translate key='HELP'}></span>
                        </a>
                    </div>
                    <div class="clear"></div>
                    <{if $extension_mod}>
                    <h4 class="cp-cat"><{translateTheme key='SYSTEM_EXTENSIONS'}></h4>
                    <div class="cp-icon">
                        <{foreach item=plug from=$extension_mod}>
                        <{if $plug->getInfo('hasAdmin') || !is_array($plug->getInfo('config'))}>
                        <a class="xo-tooltip" href="<{$xoops_url}>/modules/<{$plug->getInfo('dirname')}>/<{$plug->getInfo('adminindex')}>" title="<{$plug->getInfo('description')}>">
                            <img src="<{$plug->getInfo('logo_large')}>" alt="<{$plug->getInfo('name')}>"/>
                            <span><{$plug->getInfo('name')}></span>
                        </a>
                        <{elseif is_array($plug->getInfo('config'))}>
                        <a class="xo-tooltip" href="<{$xoops_url}>/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=<{$plug->getInfo('mid')}>" title="<{$plug->getInfo('description')}>">
                            <img src="<{$plug->getInfo('logo_large')}>" alt="<{$plug->getInfo('name')}>"/>
                            <span><{$plug->getInfo('name')}></span>
                        </a>
                        <{/if}>
                        <{/foreach}>
                    </div>
                    <{/if}>
                </div>
            </div>
        </div>
        <div class="mod-icons">
            <div class="outer">
                <div class="xo-window">
                    <div class="xo-window-title">
                        <span class="ico ico-box"></span>&nbsp;<{translateTheme key='INSTALLED_MODULES'}>
                        <a class="down" href="javascript:;">&nbsp;</a>
                    </div>
                    <div class="xo-window-data">
                        <{if count($module_menu) > 1 }>
                        <table class="condensed-table">
                            <{foreach item=mod_list from=$module_menu}>
                            <{if $mod_list->getInfo(dirname) != 'system'}>
                            <tr>
                                <td class="col-md-1"><img src="<{$mod_list->getInfo('logo_small')}>" alt=""/></td>
                                <td><a href="<{$mod_list->getInfo('link_admin')}>"><{$mod_list->getVar(name)}></a></td>
                            </tr>
                            <{/if}>
                            <{/foreach}>
                        </table>
                        <{else}>
                        <div class="alert alert-info">
                            <i class="ico-information"></i>
                            <{translateTheme key='THERE_ARE_NO_MODULES_INSTALLED'}>
                        </div>
                        <{/if}>
                        <div class="mod-link">
                            <div class="pull-right">
                                <a class="btn btn-mini btn-info" href="<{xoAppUrl 'modules/system/admin.php?fct=modulesadmin'}>">
                                    <i class="icon-white icon-tags"></i>
                                    <{translate key='MANAGE_MODULES'}>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <br/>

            <div class="outer">
                <div class="xo-window">
                    <div class="xo-window-title">
                        <span class="ico ico-extension"></span>&nbsp;<{translateTheme key='INSTALLED_EXTENSIONS'}>
                        <a class="down" href="javascript:;">&nbsp;</a>
                    </div>
                    <div class="xo-window-data">
                        <table class="condensed-table">
                            <{foreach item=plug_list from=$extension_menu}>
                            <{if $plug_list->getInfo(install)}>
                            <tr>
                                <td class="col-md-1"><img src="<{$plug_list->getInfo('logo_small')}>" alt=""/></td>
                                <td><a href="<{$plug_list->getInfo('link_admin')}>"><{$plug_list->getInfo(name)}></a>
                                </td>
                            </tr>
                            <{assign var=have_plugins value=true}>
                            <{/if}>
                            <{/foreach}>
                        </table>
                        <div class="mod-link">
                            <div class="pull-right">
                                <a class="btn btn-mini btn-warning" href="<{xoAppUrl 'modules/system/admin.php?fct=extensions'}>">
                                    <i class="icon-white icon-tag"></i>
                                    <{translate key='MANAGE_EXTENSIONS'}>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
    </section>
    <{else}>
    <!-- Display Admin menu -->
    <{include file="admin:system/admin_tabs.tpl"}>
    <div class="xo-module-content <{if $xoops_dirname != 'system'}>modules<{/if}>"><{$xoops_contents}></div>
    <{/if}>
</div>