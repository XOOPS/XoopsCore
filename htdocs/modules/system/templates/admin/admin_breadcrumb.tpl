<ul class="breadcrumb">
    <{foreach item=breadcrumb from=$xo_admin_breadcrumb}>
        <{if $breadcrumb.home}>
            <li><a class="xo-tooltip" href="<{$breadcrumb.link}>" title="<{$breadcrumb.title}>"><b class="ico-monitor"></b></a>&nbsp;<span class="divider">/</span></li>
        <{else}>
            <{if $breadcrumb.link}>
            <li><a class="xo-tooltip" href="<{$breadcrumb.link}>" title="<{$breadcrumb.title}>"><{$breadcrumb.title}></a>&nbsp;<span class="divider">/</span></li>
            <{else}>
            <li class="active"><{$breadcrumb.title}></li>
            <{/if}>
        <{/if}>
    <{/foreach}>
</ul>