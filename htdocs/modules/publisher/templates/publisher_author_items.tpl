<h2><{$smarty.const._MD_PUBLISHER_ITEMS_SAME_AUTHOR}> <{$author_name_with_link}></h2>
<br/><img src='<{$user_avatarurl}>' border='0' alt=''/><br/>
<table width='100%' border='0'>

    <{if $total_items == 0}>
    <tr>
        <td><{$smarty.const._MD_PUBLISHER_NO_AUTHOR_ITEMS}></td>
    </tr>
    <{/if}>

    <{foreach item=category from=$categories}>
    <tr>
        <{if $rating}>
        <th colspan='4'>
            <{else}>
        <th colspan='3'>

            <{/if}> <{$category.link}>
        </th>
    </tr>
    <tr>
        <td><{$smarty.const._CO_PUBLISHER_DATESUB}></td>
        <td><{$smarty.const._CO_PUBLISHER_TITLE}></td>
        <td align='right'><{$smarty.const._MD_PUBLISHER_HITS}></td>

        <{if $rating}>
        <td align='right'><{$smarty.const._MD_PUBLISHER_VOTE_RATING}></td>
        <{/if}>
    </tr>
    <{foreach item=item from=$category.items}>
    <tr>
        <td><{$item.published}></td>
        <td><{$item.link}></td>
        <td align='right'><{$item.hits}></td>

        <{if $rating}>
        <td align='right'><{$item.rating}></td>
        <{/if}>
    </tr>
    <{/foreach}>
    <tr>
        <td colspan='2' align='left'><{$smarty.const._MD_PUBLISHER_TOTAL_ITEMS}><{$category.count_items}></td>
        <td align='right'><{$smarty.const._MD_PUBLISHER_TOTAL_HITS}><{$category.count_hits}></td>
        <{if $rating}>
        <td>&nbsp;</td>
        <{/if}>
    </tr>

    <tr>
        <{if $rating}>
        <td colspan='4'>
            <{else}>
        <td colspan='3'>
            <{/if}> &nbsp;
        </td>
    </tr>
    <{/foreach}>
</table>
