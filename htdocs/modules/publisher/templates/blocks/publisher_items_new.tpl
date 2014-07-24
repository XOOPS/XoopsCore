<table cellpadding="0" cellspacing="0" border="0">
    <{foreach item=newitems from=$block.newitems}>
    <tr class="<{cycle values="even,odd"}>"> <{if $newitems.image}>
    <td>
        <img style="padding: 1px; margin: 2px; border: 1px solid #c3c3c3" width="50" src="<{$newitems.image}>" title="<{$newitems.image_name}>" alt="<{$newitems.image_name}>" />
    </td>
    <{/if}>
    <td>
        <strong><{$newitems.link}></strong> <{if $block.show_order == '1'}> (<{$newitems.new}>) <{/if}>
        <br/> <{$newitems.poster}>
    </td>
    </tr><{/foreach}>
</table>
