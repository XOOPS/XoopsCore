</head>
<body>
    <div class="txtcenter">
        <h2><{translate key ="WHO_IS_ONLINE"}></h2>
    </div>

    <table width="100%" class="outer">
        <{foreach from=$onlineusers item=user}>
            <tr class="<{cycle values='even,odd'}>">
                <td><img src="<{$user.avatar}>" alt="<{$user.name}>" /></td>
                <td>
                <{if $user.uid != 0}>
                    <a href='javascript:window.opener.location="<{$xoops_url}>/userinfo.php?uid=<{$user.uid}>";window.close();'>
                <{/if}>
                <{$user.name}>
                <{if $user.uid != 0}>
                    </a>
                <{/if}>
                <{if $isadmin}>
                    <br /><{$user.ip}>
                <{/if}>
                </td>
                <td><{$user.module}></td>
            </tr>
        <{/foreach}>

    </table>

    <{if $nav}>
        <br />
        <div class="txtright">
            <{$nav}>
        </div>
    <{/if}>

    <{if $closebutton}>
        <br />
        <div class="txtcenter">
            <input class="btn btn-primary" value="<{translate key='A_CLOSE'}>" type="button" onclick="javascript:window.close();" />
        </div>
    <{/if}>
