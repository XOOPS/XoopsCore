    <script type="text/javascript" src="../../tiny_mce_popup.js"></script>
    <script type="text/javascript" src="../../utils/mctabs.js"></script>
    <script type="text/javascript" src="../../utils/form_utils.js"></script>
    <script type="text/javascript" src="../../utils/validate.js"></script>
    <script type="text/javascript" src="js/xoops_smilies.js"></script>

    <link href="css/xoops_smilies.css" rel="stylesheet" type="text/css" />
    <base target="_self" />
</head>
<body>
    <div class="tabs">
        <ul>
            <li id="tab_smilies_browser" class="current"><span><a href="javascript:mcTabs.displayTab('tab_smilies_browser','smilies_browser_panel');" onmousedown="return false;"><{$smarty.const._SMILIES_TINYMCE_BROWSER}></a></span></li>
            <{if $form_add}>
                <li id="tab_smilies_add" class="current"><span><a href="javascript:mcTabs.displayTab('tab_smilies_add','smilies_add_panel');" onmousedown="return false;"><{$smarty.const._SMILIES_TINYMCE_ADD}></a></span></li>
            <{/if}>
        </ul>
    </div>

    <div class="panel_wrapper">
        <div id="smilies_browser_panel" class="panel current" style="overflow:auto;">
            <{foreach from=$smileys item=smile}>
                <img onmouseover="style.cursor='pointer'" onclick="Xoops_smiliesDialog.insert(this);" src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$smile.smiley_url}>" alt="<{$smile.smiley_emotion}>" class="xoops_smilies" />
            <{/foreach}>
            <div>
                <a class="xoops_smilies" href="xoops_smilies.php?op=more" title="<{translate key='MORE'}>"><{translate key='MORE'}></a>
            </div>
            <div class="mceActionPanel">
                <input type="button" id="button" name="button" class="btn btn-warning" value="<{translate key='CLOSE'}>" onclick="tinyMCEPopup.close();" />
            </div>
        </div>

        <{if $form_add}>
            <div id="smilies_add_panel" class="panel" style="overflow:none;">
                <{$form_add}>
            </div>
        <{/if}>
    </div>
</body>
</html>