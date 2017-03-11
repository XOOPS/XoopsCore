{if $block.count}
    <ul class="nav nav-list">
        {foreach item=item from=$block.waiting}
            <li>
                <a href="{$item.link}" title="{$item.name}">
                    <span class="glyphicon {$item.icon}"></span>
                    {$item.name}&nbsp;:&nbsp;{$item.count}
                </a>
            </li>
        {/foreach}
    </ul>
{/if}
