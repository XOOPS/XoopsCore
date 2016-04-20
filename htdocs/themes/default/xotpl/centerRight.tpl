{if $xoBlocks.page_topright}
    <div class="col-sm-6 col-md-6 pull-right">
        {foreach item=block from=$xoBlocks.page_topright}
        	<div class="xoops-blocks">
                {if $block.title}<h4>{$block.title}</h4>{/if}
                {$block.content}
            </div>
        {/foreach}
    </div>
{/if}
