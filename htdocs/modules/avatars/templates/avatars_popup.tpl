<script type='text/javascript'>
<!--//
function myimage_onclick(counter)
{
    window.opener.xoopsGetElementById("user_avatar").options[counter].selected = true;
    showAvatar();
    window.opener.xoopsGetElementById("user_avatar").focus();
    window.close();
}
function showAvatar()
{
    window.opener.xoopsGetElementById("avatar").src = '<{$smarty.const.XOOPS_UPLOAD_URL}>/' + window.opener.xoopsGetElementById("user_avatar").options[window.opener.xoopsGetElementById("user_avatar").selectedIndex].value;
}
//-->
</script>
<link rel="stylesheet" href="<{$xoops_url}>/modules/avatars/css/admin.css" type="text/css" />
<link rel="stylesheet" href="<{$xoops_url}>/media/xoops/css/moduladmin.css" type="text/css" />
</head>
<body>
    <div style="margin: 20px;">
        <div class="xo-moduleadmin-infobox outer">
            <div class="xo-window">
                <div class="xo-window-title">
                    <img src="<{$xoops_url}>/media/xoops/images/icons/16/avatar_system.png" alt="" />&nbsp;<{translate key="AVAILABLE_AVATARS" dirname="avatars"}>
                    <a class="down" href="javascript:;">&nbsp;</a>
                </div>
                <div class="xo-window-data">
                        <{foreach from=$avatars item=avatar name=foo}>
                        <div class="cp-avatar">
                            <div class="xo-thumbimg">
                                <img class="xo-tooltip" src="<{thumbnail image="uploads/`$avatar.avatar_file`" w=128 h=128}>" alt="<{$avatar.avatar_name}>" title="<{$avatar.avatar_name}>"/>
                            </div>
                            <div class="xo-actions txtcenter">
                                <div class="spacer bold"><{$avatar.avatar_name}></div>
                                <button class="btn btn-mini" type="button" onclick="myimage_onclick('<{$smarty.foreach.foo.iteration}>')"><{translate key="A_SELECT"}></button>
                            </div>
                        </div>
                        <{/foreach}>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <{if $closebutton}>
        <br />
        <div class="txtcenter">
            <input class="btn btn-primary" value="<{translate key='A_CLOSE'}>" type="button" onclick="javascript:window.close();" />
        </div>
    <{/if}>