<table cellspacing="0">
    <tr>
        <td id="mainmenu">
            <{if $block.currentcat|default:false}> <{$block.currentcat}> <{/if}> <{foreach item=category from=$block.categories|default:[]}> <{$category.categoryLink}> <{if $category.items|default:false}> <{foreach item=item from=$category.items}> <{$item.titleLink}> <{/foreach}> <{/if}> <{/foreach}>

        </td>
    </tr>
</table>