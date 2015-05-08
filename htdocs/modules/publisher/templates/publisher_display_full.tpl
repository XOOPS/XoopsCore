<{include file='module:publisher/publisher_header.tpl'}>

<!-- if we are on the index page OR inside a category that has subcats OR (inside a category with no subcats AND $display_category_summary is set to TRUE), let's display the summary table ! //--><{if $indexpage || $category.subcats || ($category && $display_category_summary)}>

<{if $collapsable_heading == 1}>
<div class="publisher_collaps_title">
    <a href='javascript:;' onclick="toggle('toptable'); toggleIcon('toptableicon')"><img id='toptableicon' src='<{$publisher_url}>/images/links/close12.gif' alt=''/></a>&nbsp;<{$lang_category_summary}>
</div>
<div id='toptable'>
    <span class="publisher_collaps_info""><{$lang_category_summary}></span>        <!-- Content under the collapsable bar //-->    <{/if}>

    <{include file='module:publisher/publisher_categories_table.tpl'}>

    <{if $collapsable_heading == 1}>
</div>    <{/if}>
<br/><!-- End of if !$category || $category.subcats || ($category && $display_category_summary) //--><{/if}><{if $items}>    <{if $collapsable_heading == 1}>
<div class="publisher_collaps_title">
    <a href='javascript:;' onclick="toggle('bottomtable'); toggleIcon('bottomtableicon')";><img id='bottomtableicon' src='<{$publisher_url}>/images/links/close12.gif' alt=''/></a>&nbsp;<{$lang_items_title}>
</div>
<div id='bottomtable'>
    <span class="publisher_collaps_info"><{$smarty.const._MD_PUBLISHER_ITEMS_INFO}></span> <{/if}>
    <div align="right"><{$navbar}></div>

    <!-- Start item loop -->    <{foreach item=item from=$items}> <{include file="module:publisher/publisher_singleitem.tpl" item=$item}> <{/foreach}>    <!-- End item loop -->

    <div align="right"><{$navbar}></div>
    <{if $collapsable_heading == 1}>
</div>    <{/if}><!-- end of if $items --> <{/if}>

<{include file='module:publisher/publisher_footer.tpl'}>