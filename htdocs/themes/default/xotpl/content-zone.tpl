{if $xoBlocks.canvas_left && $xoBlocks.canvas_right}
    <div class="col-sm-6 col-md-6">
        {elseif $xoBlocks.canvas_left}
            <div class="col-sm-9 col-md-9">
                {elseif $xoBlocks.canvas_right}
                    <div class="col-sm-9 col-md-9">
                        {else}
                            <div class="col-sm-12 col-md-12">
{/if}
    {include file="$theme_tpl/contents.tpl"}

    <div class="row">
        {include file="$theme_tpl/centerBlock.tpl"}
        {include file="$theme_tpl/centerLeft.tpl"}
        {include file="$theme_tpl/centerRight.tpl"}
    </div>
</div>
