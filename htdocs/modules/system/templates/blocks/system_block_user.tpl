<style type="text/css">
    <{foreach from=$block.modules item=module}>
        <{if $module.image|default:false}>
        .<{$module.dirname|default:''}>-icon, .nav .active a .<{$module.dirname|default:''}>-icon {
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
    <{foreach from=$block.modules item=module}>
        <li<{if $module.link == $block.active_url}> class="active"<{/if}>>
            <a <{if $module.class|default:false}>class="<{$module.class}>"<{/if}> href="<{$module.link}>" title="<{$module.name}>" <{if $module.rel|default:false}>rel="<{$module.rel}>"<{/if}>>
                <i class="<{$module.icon}> <{$module.dirname|default:''}>-icon"></i>
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
</ul>
