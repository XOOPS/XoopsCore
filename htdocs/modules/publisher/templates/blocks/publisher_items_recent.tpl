<table class="outer" cellspacing="1">

    <tr>
        <td class="head"><{$block.lang_title}></td>
        <td class="head" align="left"><{$block.lang_category}></td>
        <td class="head" align="center" width="100"><{$block.lang_poster}></td>
        <td class="head" align="right" width="120"><{$block.lang_date}></td>
    </tr>

    <{foreach item=item from=$block.items}>
    <tr class="<{cycle values=" even
    ,odd"}>">
    <td><{$item.itemlink}></td>
    <td align="left"><{$item.categorylink}></td>
    <td align="center"><{$item.poster}></td>
    <td align="right"><{$item.date}></td>
    </tr><{/foreach}>

</table>

<div style="text-align:right; padding: 5px;">
    <a href="<{$publisher_url}>"><{$block.lang_visitItem}></a>
</div>
