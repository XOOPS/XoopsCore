<{include file="admin:system/admin_breadcrumb.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<{if $index}>
<br class="clear" />
<div class="spacer">
    <table class="outer ui-corner-all" cellspacing="1">
        <tr>
            <th><{translate key='C_YOUR_THEMES' dirname='system'}></th>
            <th>&nbsp;</th>
        </tr>
		<tr>
			<td class="aligntop width10"><div id="fileTree" class="display_folder"></div></td>
			<td class="aligntop">
				<div id="display_form">
                <{include file="module:system/system_form.tpl"}>
                </div>
				<div id="display_contenu"></div>
				<div id='display_message' class="txtcenter" style="display:none;"></div>
				<div id='loading' class="txtcenter" style="display:none;"><br /><br /><img src="images/loading.gif" title="Loading" alt="Loading" /></div>
			</td>
		</tr>
    </table>

<br class="clear" />
</div>
<{else}>
    <br />
    <{if $verif}>
        <{$infos}>
    <{else}>
        <div class="txtcenter"><{translate key='NO_FILES_CREATED' dirname='system'}></div>
    <{/if}>
<{/if}>
