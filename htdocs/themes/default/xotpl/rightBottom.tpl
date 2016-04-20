{if $xoBlocks.page_bottomright}
<aside class="col-sm-4 col-md-4">
    {foreach item=block from=$xoBlocks.page_bottomright}
        <div class="xoops-bottom-blocks">
            {if $block.title}<h4>{$block.title}</h4>{/if}
            {$block.content}
        </div>
    {/foreach}
</aside>
{/if}
