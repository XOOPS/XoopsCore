<{if $block.template == 'normal'}>
<div style="width:100%;">
    <{section name=i loop=$block.columns}>
    <div style="width: <{$block.columnwidth}>%;" class="publisher-left">
        <{foreach item=item from=$block.columns[i]}>
        <div class="publisher-box">
            <div class="publisher-section publisher-clearfix">
                <a href="<{$item.categoryurl}>" title="<{$item.item_cat_description}>"><span><{$item.item_cat_name}></span></a>
            </div>
            <div class="publisher-content clearfix">
                <h4 class="publisher-title">
                    <a href="<{$item.itemurl}>" title="<{$item.item_cleantitle}>"><{$item.item_title}></a>
                </h4>

                <p>
                    <{if $item.item_image != ''}>
                    <img src="<{$item.item_image}>" alt="<{$item.item_cleantitle}>" align="left" width="100" /> <{/if}> <{$item.item_summary}>
                </p>
            </div>

            <{if $item.subitem}>
            <strong class="publisher-more"><{$smarty.const._MB_PUBLISHER_MORE}></strong>
            <ul class="publisher-links">
                <{foreach item=subitem from=$item.subitem}>
                <li>
                    <a title="<{$subitem.title}>" href="<{$subitem.itemurl}>"> <{$subitem.title}></a>
                </li>
                <{/foreach}>
            </ul>
            <{/if}>
        </div>
        <{/foreach}>
    </div>
    <{/section}>
</div><{/if}><{if $block.template == 'extended'}>
<div style="width:100%;">
    <{section name=i loop=$block.columns}>
    <div style="width: <{$block.columnwidth}>%;" class="publisher-left">
        <{foreach item=item from=$block.columns[i]}>
        <div class="publisher-box">

            <h3 class="publisher-title">
                <a href="<{$item.itemurl}>" title="<{$item.item_title}>"><{$item.item_title}></a>
            </h3>

            <div style="float:right; width:60%">

                <div class="publisher-content clearfix">
                    <{if $item.item_image != ''}>
                    <img src="<{$item.item_image}>" alt="<{$item.item_cleantitle}>" align="right" width="100" /> <{/if}>
                    <p><{$item.item_summary}></p>

                    <p>
                        <a href="<{$item.itemurl}>" title="<{$item.item_title}>"><{$smarty.const._MB_PUBLISHER_READMORE}></a>
                    </p>
                </div>

            </div>

            <{if $item.subitem}>
            <div style="float:left; width:40%;">
                <br/>
                <strong class="publisher-more"><{$smarty.const._MB_PUBLISHER_MORE}></strong>
                <br/>
                <ul class="publisher-links">
                    <{foreach item=subitem from=$item.subitem}>
                    <li>
                        <a title="<{$subitem.summary}>" href="<{$subitem.itemurl}>"> <{$subitem.title}></a>
                    </li>
                    <{/foreach}>
                </ul>

            </div>
            <{/if}>
        </div>

        <{/foreach}>
    </div>
    <{/section}>
</div><{/if}>
