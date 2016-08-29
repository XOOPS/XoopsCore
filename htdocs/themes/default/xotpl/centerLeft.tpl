{if $xoBlocks.page_topleft}
    <div class="col-sm-6 col-md-6 pull-left">
        {foreach item=block from=$xoBlocks.page_topleft}
        	<div class="xoops-blocks">
                {if $block.title}<h4>{$block.title}</h4>{/if}
                {$block.content}
            </div>
        {/foreach}
    </div>
{/if}
