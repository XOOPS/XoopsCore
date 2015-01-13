<{if $xo_admin_box}>
<div class="<{$class}>">
    <{foreach item=box from=$xo_admin_box}>
    <div class="xo-moduleadmin-infobox outer" <{$box.extra}>>
        <div class="xo-window">
            <div class="xo-window-title">
                <img src="<{$xoops_url}>/media/xoops/images/icons/16/<{$box.type}>.png" alt="<{$box.title}>" />&nbsp;<{$box.title}>
                <a class="down" href="javascript:;">&nbsp;</a>
            </div>
            <div class="xo-window-data">
                <ul>
                    <{foreach item=line from=$box.line}>
                    <li class="<{$line.color}>"><{$line.text}></li>
                    <{/foreach}>
                </ul>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <br />
    <{/foreach}>
</div>
<{/if}>