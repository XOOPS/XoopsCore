<style type="text/css">
    {foreach from=$block.modules|default:[] item=module}
        {if $module.image|default:false}
        .{$module.dirname}-icon, .nav .active a .{$module.dirname}-icon {
            background-image: url('{$module.image}');
            background-position: 0 0;
            width: 16px;
            height: 16px;
            line-height: 16px;
        }
        {/if}
    {/foreach}
</style>
<ul class="nav nav-pills nav-stacked">
    <li class="{if !$block.nothome|default:false}active{/if}">
        <a href="{xoAppUrl}" title="{$block.lang_home}">
            <span class="glyphicon glyphicon-home {if !$block.nothome|default:false}icon-white{/if}"></span>
            {$block.lang_home}
        </a>
    </li>
    <!-- start module menu loop -->
    {foreach item=module from=$block.modules|default:[]}
    <li class="{if $module.highlight|default:false}active{/if}">
        <a class="" href="{$xoops_url}/modules/{$module.dirname}/" title="{$module.name}">
            <span class="glyphicon {$module.dirname}-icon{if $module.highlight|default:false} icon-white{/if}"></span>
            {$module.name}
        </a>
        {if $module.sublinks|default:false}
        <ul class="nav list-group">
        {foreach item=sublink from=$module.sublinks}
            <li class="">
                <a class="list-group-item" href="{$sublink.url}" title="{$sublink.name}">&nbsp;&nbsp;<span class="glyphicon glyphicon-menu-right"></span>  {$sublink.name}</a>
            </li>
        {/foreach}
        </ul>
        {/if}
    </li>
    {/foreach}
    <!-- end module menu loop -->
</ul>
