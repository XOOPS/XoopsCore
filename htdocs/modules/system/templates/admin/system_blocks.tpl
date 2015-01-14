<!-- Breadcrumb Header -->
<{include file="admin:system/admin_breadcrumb.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<script type="text/javascript">
    IMG_ON = '<{xoAdminIcons 'success.png'}>';
    IMG_OFF = '<{xoAdminIcons 'cancel.png'}>';
</script>
<{if $filterform|default:false}>
<{include file="admin:system/admin_buttons.tpl"}>
<div class="clear"></div>
<div id="xo-block-dragndrop">
    <table class="outer">
        <thead>
        <tr>
            <th>
                <form name="<{$filterform.name}>" id="<{$filterform.name}>" action="<{$filterform.action}>" method="<{$filterform.method}>" <{$filterform.extra}> >
                    <div class="xo-blocksfilter">
                    <{foreach item=element from=$filterform.elements}>
                    <{if $element.hidden != true}>

                    <div class="xo-caption"><{$element.caption}></div>
                    <div class="xo-element"><{$element.body}></div>
                    <{else}>
                    <{$element.body}>
                    <{/if}>
                    <{/foreach}>
                    </div>
                </form>
            </th>
        </tr>
        </thead>
        <tr>
            <td>
                <table id="xo-block-managment">
                    <tr>
                        <td side="0" class="xo-blocksection" rowspan="3" id="xo-leftcolumn">
                            <div class="xo-title"><{translate key='LEFT'}></div>
                            <{include file="admin:system/system_blocks_item.tpl" blocks=$blocks side=0}>
                        </td>
                        <td side="3" class="xo-blocksection">
                            <div class="xo-title"><{translate key='TOP_LEFT' dirname='system'}></div>
                            <{include file="admin:system/system_blocks_item.tpl" blocks=$blocks side=3}>
                        </td>
                        <td side="5" class="xo-blocksection">
                            <div class="xo-title"><{translate key='TOP_CENTER' dirname='system'}></div>
                            <{include file="admin:system/system_blocks_item.tpl" blocks=$blocks side=5}>
                        </td>
                        <td side="4" class="xo-blocksection">
                            <div class="xo-title"><{translate key='TOP_RIGHT' dirname='system'}></div>
                            <{include file="admin:system/system_blocks_item.tpl" blocks=$blocks side=4}>
                        </td>
                        <td side="1" class="xo-blocksection" rowspan="3" id="xo-rightcolumn">
                            <div class="xo-title"><{translate key='RIGHT'}></div>
                            <{include file="admin:system/system_blocks_item.tpl" blocks=$blocks side=1}>
                        </td>
                    </tr>
                    <tr style="height:30px;">
                        <td colspan="3" class="xo-blockContent width5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td side="7" class="xo-blocksection">
                            <div class="xo-title"><{translate key='BOTTOM_LEFT' dirname='system'}></div>
                            <{include file="admin:system/system_blocks_item.tpl" blocks=$blocks side=7}>
                        </td>
                        <td side="9" class="xo-blocksection">
                            <div class="xo-title"><{translate key='BOTTOM_CENTER' dirname='system'}></div>
                            <{include file="admin:system/system_blocks_item.tpl" blocks=$blocks side=9}>
                        </td>
                        <td side="8" class="xo-blocksection">
                            <div class="xo-title"><{translate key='BOTTOM_RIGHT' dirname='system'}></div>
                            <{include file="admin:system/system_blocks_item.tpl" blocks=$blocks side=8}>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<{/if}>
<div id="xo-block-add" <{if $filterform|default:false}>class="hide"<{/if}>>
    <{if !$filterform|default:false}><br /><{/if}>
    <{$blockform|default:''}>
</div>
<!-- Preview block -->
<div id="xo-preview-block" class="hide"></div>
