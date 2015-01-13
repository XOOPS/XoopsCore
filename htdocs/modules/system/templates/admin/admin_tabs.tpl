<{if $xo_module_menu_top|default:false}>
<div class="spacer pull-right">|
    <{foreach item=top from=$xo_module_menu_top}>
    <a href="<{$top.link}>"><{$top.name}></a> |
    <{/foreach}>
</div>
<{/if}>
<{if $xo_module_menu_tab|default:false}>
<ul class="xo-module-tabs nav nav-tabs">
    <{foreach item=tab from=$xo_module_menu_tab}>
    <li class="<{if $tab.current}>active<{/if}>"><a href="<{$tab.link}>"><{$tab.name}></a></li>
    <{/foreach}>
</ul>
<{/if}>