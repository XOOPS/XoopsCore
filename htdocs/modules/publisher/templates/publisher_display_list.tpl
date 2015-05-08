<{include file='module:publisher/publisher_header.tpl'}>

<!-- Display type is bullet list -->
<div class="publisher_infotitle"><{$lang_category_summary}></div>
<span class="publisher_collaps_info"><{$category.description}><br><br></span><!-- if the option is set, add the last item column --><{if $category && $displaylastitem == 1 && $items}>
<div id="publisher_bullet_lastitem">
    <strong><{$smarty.const._MD_PUBLISHER_LAST_SMARTITEM}> : <{$category.last_title_link}>
</div>    <{/if}><br/>

<!-- if we are on the index page OR inside a category that has subcats OR (inside a category with no subcats AND $display_category_summary is set to TRUE, let's display the summary table ! //--><{if $indexpage || $category.subcats || ($category && $display_category_summary)}>    <{include file='module:publisher/publisher_categories_table.tpl'}>
<br/><!-- End of if !$category || $category.subcats || ($category && $display_category_summary) //--><{/if}>

<{if $items}>    <{if $collapsable_heading == 1}>
<div class="publisher_collaps_title">
    <a href='javascript:;' onclick="toggle('bottomtable'); toggleIcon('bottomtableicon')";><img id='bottomtableicon' src='<{$publisher_url}>/images/links/close12.gif' alt=''/></a>&nbsp;<{$lang_items_title}>
</div>
<div id='bottomtable'>
    <{else}> <{if $subcats}>
    <div class="publisher_collaps_title"><strong><{$lang_items_title}></strong></div>
    <{/if}>    <!-- Content under the collapsable bar //-->    <{/if}>

    <table border="0" width="90%" cellspacing="0" cellpadding="0" align="center" class="outer">
        <tr>
            <td align="left" class="itemHead" width='60%'>
                <strong>&nbsp;&nbsp;<{$smarty.const._CO_PUBLISHER_TITLE}></strong></td>
            <{if $display_date_col == 1}>
            <td align="center" class="itemHead" width="30%">
                <strong><{$smarty.const._MD_PUBLISHER_DATESUB}></strong></td>
            <{/if}> <{if $display_hits_col == 1}>
            <td align="center" class="itemHead" width="10%">
                <strong><{$smarty.const._MD_PUBLISHER_HITS}></strong></td>
            <{/if}>
        </tr>
        <!-- Start item loop -->
        <ul>
            <{foreach item=item from=$items}>
            <tr>
                <td class="odd">
                    <div class="publisher_list" align="left">
                        <li><{$item.titlelink}></li>
                        <{if $show_subtitle && $item.subtitle}>
                        <br /><em><{$item.subtitle}></em>
                        <{/if}>
                    </div>
                </td>

                <{if $display_date_col == 1}>
                <td class="odd" align="left">
                    <div class="publisher_list" align="center"><{$item.datesub}></div>
                </td>
                <{/if}>

                <{if $display_hits_col == 1}>
                <td class="odd" align="left">
                    <div class="publisher_list" align="center"><{$item.counter}></div>
                </td>
                <{/if}>

            </tr>
            <{/foreach}>
        </ul>
        <!-- End item loop -->
        <tr></tr>
    </table>
    <div align="right"><{$navbar}></div>
    <{if $collapsable_heading == 1}>
</div>    <{/if}><!-- end of if $items --><{/if}>

<{if !$subcats && !$items}>
<div class="publisher_infotext"><{$smarty.const._MD_PUBLISHER_EMPTY}></div><{/if}>

<{include file='module:publisher/publisher_footer.tpl'}>