<{if $xo_admin_tips|default:false}>
<div class="well tips">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <img class="floatleft xo-tooltip" src="<{xoAdminIcons 'tips.png'}>" alt="<{translate key='TIPS'}>" title="<{translate key='TIPS'}>" />
    <div class="floatleft"><{$xo_admin_tips}></div>
    <div class="clear">&nbsp;</div>
</div>
<{/if}>