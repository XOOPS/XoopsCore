<style type="text/css">
    <{foreach from=$block.modules item=module}>
        <{if $module.image|default:false}>
        .<{$module.dirname}>-icon, .nav .active a .<{$module.dirname}>-icon {
            background-image: url('<{$module.image}>');
            background-position: 0 0;
            width: 16px;
            height: 16px;
            line-height: 16px;
        }
        <{/if}>
    <{/foreach}>
</style>
<ul class="nav nav-list">
    <li class="<{if !$block.nothome|default:false}>active<{/if}>">
        <a href="<{xoAppUrl}>" title="<{$block.lang_home}>">
            <i class="icon-home <{if !$block.nothome|default:false}>icon-white<{/if}>"></i>
            <{$block.lang_home}>
        </a>
    </li>
    <!-- start module menu loop -->
    <{foreach item=module from=$block.modules}>
    <li class="<{if $module.highlight|default:false}>active<{/if}>">
        <a class="" href="<{$xoops_url}>/modules/<{$module.dirname}>/" title="<{$module.name}>">
            <i class="icon-tags <{$module.dirname}>-icon<{if $module.highlight|default:false}> icon-white<{/if}>"></i>
            <{$module.name}>
        </a>
        <{if $module.sublinks|default:false}>
        <ul class="nav nav-list">
        <{foreach item=sublink from=$module.sublinks}>
            <li>
                <a class="" href="<{$sublink.url}>" title="<{$sublink.name}>"><{$sublink.name}></a>
            </li>
        <{/foreach}>
        </ul>
        <{/if}>
    </li>
    <{/foreach}>
    <!-- end module menu loop -->
</ul>