<{includeq file="admin:system|system_header.tpl"}>
<{if $help}>
<div class="help-menu">
    <div class="system-help outer">
        <div class="xo-window">
            <div class="xo-window-title">
                <span class="ico ico-application"></span>&nbsp;<{translate key='HELP'}>
            </div>
            <div class="xo-window-data">
                <{foreach item=help from=$help}>
                    <div><a href="<{$help.link}>"><{$help.name}></a></div>
                <{/foreach}>
            </div>
        </div>
    </div>
</div>
<{/if}>
<{if $list_mods}>
<div class="help-menu">
    <div class="system-help outer">
        <div class="xo-window">
            <div class="xo-window-title">
                <span class="ico ico-application"></span>&nbsp;<{translate key='HELP'}>
            </div>
            <div class="xo-window-data">
                <{foreach item=row from=$list_mods}>
                    <h4 class="head"><{$row.name}></h4>
                    <{foreach item=list from=$row.help_page}>
                        <div title="<{$list.name}>"><a href="<{$list.link}>"><{$list.name}></a></div>
                    <{/foreach}>
                <{/foreach}>
            </div>
        </div>
    </div>
</div>
<{/if}>
<div class="help-content">
    <div class="system-help outer">
        <div class="xo-window">
            <div class="xo-window-title">
                <span class="ico ico-application"></span>&nbsp;<{translate key='HELP'}>
            </div>
            <div class="xo-window-data">
                <{$helpcontent}>
                <{if $help_module}>
                <div class="txtcenter">
                    <a class="btn btn-primary btn-mini" href="<{$xoops_url}>/modules/<{$moddirname}>/admin/index.php"><i class="icon-home icon-white"></i> <{$modname}></a>
                </div>
                <{/if}>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
