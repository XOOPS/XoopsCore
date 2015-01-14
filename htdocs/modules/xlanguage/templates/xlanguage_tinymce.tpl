    <script type="text/javascript" src="../../tiny_mce_popup.js"></script>
    <script type="text/javascript" src="../../utils/mctabs.js"></script>
    <script type="text/javascript" src="../../utils/form_utils.js"></script>
    <script type="text/javascript" src="../../utils/validate.js"></script>
    <script type="text/javascript" src="js/xoops_xlanguage.js"></script>

    <link href="css/xoops_xlanguage.css" rel="stylesheet" type="text/css" />
    <base target="_self" />
</head>
<body>
    <div class="tabs">
        <ul>
            <li id="tab_xlanguage_browser" class="current"><span><a href="javascript:mcTabs.displayTab('tab_xlanguage_browser','xlanguage_browser_panel');" onmousedown="return false;"><{$smarty.const._XLANGUAGE_TINYMCE_INS}></a></span></li>
            <{if $form_add}>
                <li id="tab_xlanguage_add" class="current"><span><a href="javascript:mcTabs.displayTab('tab_xlanguage_add','xlanguage_add_panel');" onmousedown="return false;"><{$smarty.const._XLANGUAGE_TINYMCE_ADD}></a></span></li>
            <{/if}>
        </ul>
    </div>
    <br />

    <div class="panel_wrapper">
        <div id="xlanguage_browser_panel" class="panel current" style="overflow:none;">
            <{$form_txt}>
        </div>

        <{if $form_add}>
            <div id="xlanguage_add_panel" class="panel" style="overflow:none;">
                <{$form_add}>
            </div>
        <{/if}>
    </div>
</body>
</html>