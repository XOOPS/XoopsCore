<ul class="breadcrumb">
    <{foreach item=breadcrumb_item from=$xmf_breadcrumb_items name=loop}>
        <{if $breadcrumb_item.link|default:false}>
            <li><a href="<{$breadcrumb_item.link}>"><{$breadcrumb_item.caption}></a>
        <{else}>
            <li class="active"><{$breadcrumb_item.caption}>
        <{/if}>
        <{if !$smarty.foreach.loop.last}><span class="divider">&raquo;</span><{/if}>
        <{/foreach}>
        </li>
</ul>
