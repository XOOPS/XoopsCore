<{if $block.mode == 'list'}>
    <{includeq file="block:page|page_blocks_list.tpl"}>
<{elseif $block.mode == 'content'}>
    <{includeq file="block:page|page_blocks_content.tpl"}>
<{/if}>
