{if $block.count}
    <style type="text/css">
    {foreach item=item from=$block.waiting}
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
    <ul class="nav nav-list">
        {foreach item=item from=$block.waiting}
            <li>
                <a href="{$item.link}" title="{$item.name}">
                    <i class="glyphicon {$item.icon}"></i>
                    {$item.name}&nbsp;:&nbsp;{$item.count}
                </a>
            </li>
        {/foreach}
    </ul>
{/if}
