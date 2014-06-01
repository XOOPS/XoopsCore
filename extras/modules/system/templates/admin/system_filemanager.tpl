<!-- Filemanager -->
<{includeq file="module:system|system_header.html"}>
<{if $index}>
<br /><div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <table cellpadding="0" cellspacing='1' class="outer">
        <tr>
            <th><{$smarty.const._AM_SYSTEM_FILEMANAGER_DIRECTORY}></th>
            <th><{$smarty.const._AM_SYSTEM_FILEMANAGER_FILES}></th>
        </tr>
        <tr>
            <td class="aligntop width10">
                <div id="fileTree" class="display_folder"></div>
            </td>
            <td class="aligntop">
                <div class="edit_file" id="edit_file"></div>
                <div class="upload_file" id="upload_file"></div>
                <div id='loading' align="center" style="display:none;"><br /><br /><img src="./images/loading.gif" title="Loading" alt="Loading" /></div>
                <div class="display_file" id="display_file">
                <div class="xo-btn-actions">
					<div class="xo-buttons">
						<button class="ui-corner-all tooltip" type="button" onclick="filemanager_load_tree();filemanager_display_file('', 0)" title="<{$smarty.const._AM_SYSTEM_FILEMANAGER_HOME}>">
							<img src="<{xoAdminIcons home.png}>" alt="<{$smarty.const._AM_SYSTEM_FILEMANAGER_HOME}>" />
						</button>
						<button class="ui-corner-all tooltip" type="button" onclick="filemanager_add_directory('')" title="<{$smarty.const._AM_SYSTEM_FILEMANAGER_ADDDIR}>">
							<img src="<{xoAdminIcons folder_add.png}>" alt="<{$smarty.const._AM_SYSTEM_FILEMANAGER_ADDDIR}>" />
						</button>
						<button class="ui-corner-all tooltip" type="button" onclick="filemanager_add_file('')" title="<{$smarty.const._AM_SYSTEM_FILEMANAGER_ADDFILE}>">
							<img src="<{xoAdminIcons add.png}>" alt="<{$smarty.const._AM_SYSTEM_FILEMANAGER_ADDFILE}>" />
						</button>
						<button class="ui-corner-all tooltip" type="button" onclick="filemanager_upload('')" title="<{$smarty.const._AM_SYSTEM_FILEMANAGER_UPLOAD}>">
							<img src="<{xoAdminIcons upload.png}>" alt="<{$smarty.const._AM_SYSTEM_FILEMANAGER_UPLOAD}>" />
						</button>
					</div>
					<div class="clear">&nbsp;</div>
				</div>
                    <table cellpadding="0" cellspacing='0'  border="0" align="center">
                        <tr>
                            <{foreach item=files from=$files}>
                                <td align="center" width="<{$width}>%">
                                    <div style="border: 1px solid #cccccc; ">
                                        <table cellpadding="0" cellspacing='0'>
                                            <tr class="odd">
                                                <td align="left"><{$files.chmod}></td>
                                                <td align="right"><{if $files.edit}><img class='cursorpointer' src="<{xoAdminIcons edit.png}>" onclick='filemanager_edit_file("<{$files.path_file}>", "<{$files.path}>", "<{$files.file}>", "<{$files.extension}>");' width='16' alt='edit' /><{/if}>&nbsp;<img class='cursorpointer' src="<{xoAdminIcons delete.png}>" onclick='filemanager_confirm_delete_file("<{$files.path_file}>", "<{$files.path}>", "<{$files.file}>");' width='16' alt='delete' /></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center" height="60px"><br /><{$files.img}></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center"><{$files.file}><br /><br /></td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                        <{if $files.newline}>
                        </tr>
                        <tr>
                        <{/if}>
                            <{/foreach}>
                        </tr>
                    </table>
                </div><br />
                <div id='confirm_delete' align="center" style="display:none;"></div>
            </td>
        </tr>
    </table>
    <br class="clear">
</div>
<{/if}>