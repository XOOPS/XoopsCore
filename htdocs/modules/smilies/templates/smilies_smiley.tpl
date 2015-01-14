<script type="text/javascript">
<!--//
function doSmilie(addSmilie) {
    var textareaDom = window.opener.xoopsGetElementById("<{$target}>");
    xoopsInsertText(textareaDom, addSmilie);
    textareaDom.focus();
    window.close();
    return;
}
//-->
</script>
</head>
<body>
    <div style="margin: 20px;">
        <div class="txtcenter">
            <h2><{translate key='SMILIES'}></h2>
            <h4><{translate key='CLICK_A_SMILIE_TO_INSERT_INTO_MESSAGE'}></h4>
        </div>

        <table class="table table-striped table-bordered outer">
            <tr class="head">
                <td><{translate key='CODE'}></td>
                <td><{translate key='EMOTION'}></td>
                <td><{translate key='IMAGE'}></td>
            </tr>

            <{foreach from=$smileys item=smile}>
                <tr class="<{cycle values='even,odd'}>">
                    <td><{$smile.smiley_code}></td>
                    <td><{$smile.smiley_emotion}></td>
                    <td>
                        <img onmouseover="style.cursor='pointer'" onclick="javascript:doSmilie('<{$smile.smiley_code}>');" src='<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$smile.smiley_url}>' alt='<{$smile.smiley_emotion}>' />
                    </td>
                </tr>
            <{/foreach}>

        </table>

        <{if $closebutton}>
            <br />
            <div class="txtcenter">
                <input class="btn btn-primary" value="<{translate key='A_CLOSE'}>" type="button" onclick="javascript:window.close();" />
            </div>
        <{/if}>
    </div>
