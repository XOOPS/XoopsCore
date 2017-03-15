{if $block.count}
    <style type="text/css">
    {foreach item=item from=$block.mainmenu}
        {if $item.image}
        .{$item.dirName}-icon, .nav .active a .{$item.dirName}-icon {
            background-image: url('{$item.image}');
            background-position: 0 0;
            width: 16px;
            height: 16px;
            line-height: 16px;
        }
        {/if}
    {/foreach}
</style>
    <ul class="nav nav-pills nav-stacked">
        {foreach item=item from=$block.mainmenu}
            <li{if $item.isActive} class="active"{/if}>
                <a href="{$item.link}" title="{$item.name}">
                    <i class="glyphicon {$item.icon} "></i>
                    {$item.name}
                </a>
                {if $item.subMenu}
                <ul class="nav list-group">
                {foreach item=subMenu from=$item.subMenu}
                    <li>
                        <a class="list-group-item" href="{$subMenu.link}" title="{$subMenu.name}">&nbsp;&nbsp
                            <i class="glyphicon {$subMenu.icon}"></i>
                            {$subMenu.name}
                        </a>
                    </li>
                {/foreach}
                </ul>
                {/if}
            </li>
        {/foreach}
    </ul>
{/if}
