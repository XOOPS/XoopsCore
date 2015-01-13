<{if $block.mode == 'list'}>
    <{include file="block:page/page_blocks_list.tpl"}>
<{elseif $block.mode == 'content'}>
    <{include file="block:page/page_blocks_content.tpl"}>
<{/if}>
