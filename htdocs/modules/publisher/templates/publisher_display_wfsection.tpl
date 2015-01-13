<{include file='module:publisher/publisher_header.tpl'}>

<!--<{if $collapsable_heading == 1}>
	<div class="publisher_collaps_title"><a href='javascript:;' onclick="toggle('toptable'); toggleIcon('toptableicon')"><img id='toptableicon' src='<{$publisher_url}>/images/links/close12.gif' alt='' /></a>&nbsp;<{$lang_category_summary}></div>
	<div id='toptable'>
	<span class="publisher_collaps_info""><{$lang_category_summary}></span>
<{/if}> -->

<{if $indexpage}>
<div class="item">
    <!-- Start categories loop -->        <{foreach item=category from=$categories}>
    <div class="publisher_category_index_list" style="clear: both;">
        <div class="publisher_categoryname"><{$category.categorylink}></div>
        <div>
            <{if $category.image_path}>
            <img class="publisher_category_image" src="<{$category.image_path}>" alt="<{$category.name}>" width="<{$category_list_image_width}>"/> <{/if}> <{$category.description}>
        </div>
        <{if $category.subcats}>
        <div class="publisher_subcats">
            <div class="publisher_subcats_info"><{$category.lang_subcategories}></div>
            <{foreach name=loop item=subcat from=$category.subcats}> <{$subcat.categorylink}><{if $smarty.foreach.loop.iteration < $category.subcatscount}> -<{/if}> <{/foreach}>
        </div>
        <{/if}>
        <div style="clear: both"></div>
    </div>

    <{/foreach}>        <!-- End categories loop -->
</div><{else}>
<div>
    <!-- Start categories loop -->        <{foreach item=category from=$categories}>
    <div style="clear: both;">
        <div>
            <{if $category.image_path}>
            <img class="publisher_category_image" src="<{$category.image_path}>" alt="<{$category.name}>" width="<{$category_list_image_width}>"/> <{/if}> <{$category.description}>
        </div>
        <div class="publisher_category_header">
            <{$category.header}>
        </div>
        <div style="clear: both"></div>
        <{if $category.subcats}>
        <div class="publisher_subcats">
            <div class="publisher_subcats_info"><{$category.lang_subcategories}></div>
            <{foreach name=loop item=subcat from=$category.subcats}> <{$subcat.categorylink}><{if $smarty.foreach.loop.iteration < $category.subcatscount}> -<{/if}> <{/foreach}>
        </div>
        <{/if}>
    </div>
    <{/foreach}>        <!-- End categories loop -->
</div><{/if}>

<!--<{if $collapsable_heading == 1}>
	</div>
<{/if}>-->
<div class="publisher_items_list">
    <{if $items}> <{if $collapsable_heading == 1}>
    <div class="publisher_collaps_title">
        <a href='javascript:;' onclick="toggle('bottomtable'); toggleIcon('bottomtableicon')";><img id='bottomtableicon' src='<{$publisher_url}>/images/links/close12.gif' alt=''/></a>&nbsp;<{$lang_items_title}>
    </div>
    <div id='bottomtable'>
        <span class="publisher_collaps_info"><{$smarty.const._MD_PUBLISHER_ITEMS_INFO}></span> <{/if}>
        <div align="right"><{$category.navbar}></div>

        <div class="item">
            <{foreach item=item from=$items}>

            <table>
                <tr>
                    <td style="background-color: rgb(231, 231, 231); font-weight: bold;"><{$item.datesub}></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;"><{$item.title}></td>
                </tr>
            </table>
            <table style="border-bottom: 1px solid rgb(231, 231, 231);">
                <tr>
                    <td style="padding-left: 35px;"><{$item.summary}></td>
                </tr>
                <tr></tr>
            </table>
            <table>
                <tr>
                    <td style="text-align: right;" align="right">
                        <a href="javascript:openWithSelfMain('<{$publisher_url}>/pop.php?itemid=<{$item.itemid}>', 'smartpopup', 700, 519);"><img src="<{$xoops_url}>/images/press_room_go.gif" alt="" style="vertical-align: bottom;" align="right"></a>
                    </td>
                </tr>
                <tr></tr>
            </table>
            <{/foreach}>
        </div>

        <div align="right"><{$category.navbar}></div>

        <{$press_room_footer}>

        <{if $collapsable_heading == 1}>
    </div>
    <{/if}>    <!-- end of if $items --> <{/if}>
</div>

<{include file='module:publisher/publisher_footer.tpl'}>
