<{if $xo_admin_buttons|default:false}>
<div class="xo-moduleadmin-button <{$xo_admin_buttons_position}>">
    <{foreach item=button from=$xo_admin_buttons}>
    <a class="btn btn-primary xo-tooltip" href="<{$button.link}>" title="<{$button.title}>">
        <b class="ico-<{$button.icon}>"></b>
        <{$button.title}>
    </a>
    <{/foreach}>
</div>
<br />&nbsp;<br />
<{/if}>