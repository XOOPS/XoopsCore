<h4 class="txtcenter"><{translate key='PRIVATE_MESSAGES'}></h4>
<br />
<ul class="breadcrumb">
    <li><a href="userinfo.php?uid=<{$xoops_userid}>"><{translate key='PROFILE'}></a> <span class="divider">/</span></li>
    <li><a href='viewpmsg.php'><{translate key="INBOX"}></a> <span class="divider">/</span></li>
    <li class="active"><{$subject}></li>
</ul>
<br />

<{$error_msg|default:false}>
<{if $read|default:false}>
<form name='prvmsg' method='post' action='readpmsg.php'>
    <table class="table table-bordered table-condensed">
        <tr>
            <th colspan="2">
                <{translate key="FROM"}>
            </th>
        </tr>
        <tr>
            <td rowspan="2" class="width20">
                <{if $poster|default:false}>
                    <a href='<{$xoops_url}>/userinfo.php?uid=<{$poster->getVar("uid")}>'><{$poster->getVar("uname")}></a><br />
                    <{if ( $poster_avatar != "" ) }>
                        <img src="<{$poster_avatar}>" alt='' /><br />
                    <{/if}>
                    <{if ( $poster->getVar("user_from") != "" ) }>
                        <{translate key='C_FROM'}><{$poster->getVar("user_from")}><br /><br />
                    <{/if}>
                    <{if ( $poster->isOnline() ) }>
                        <span class='bold red'><{translate key='ONLINE'}></span><br /><br />
                    <{/if}>
                <{else}>
                    <{$anonymous}>
                <{/if}>
            </td>
            <td>
                <{if $msg_image != ""}>
                    <img src='<{$xoops_url}>/images/subject/<{$msg_image}>' alt='' />
                <{/if}>
                <strong><{$subject}></strong><br />
                <br />
                <{$msg_text}><br />
                <br />
            </td>
        </tr>
        <tr>
            <td>
                <{translate key='C_SENT'}>&nbsp;<{$msg_time}>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <{if $poster|default:false}>
                <input type='button' class='btn' onclick='javascript:openWithSelfMain("pmlite.php?reply=1&amp;msg_id=<{$msg_id}>", "pmlite",750,720);' value='<{translate key="A_REPLY"}>' />
                <{/if}>
                <input type='submit' class='btn' name='delete_messages' value='<{translate key="A_DELETE"}>' />
                <input type='hidden' name='op' value='delete'>
                <input type='hidden' name='msg_id' value='<{$msg_id}>'>
                <{$token}>
            </td>
        </tr>
    </table>
</form>
<ul class="pager">
    <{if ( $previous >= 0 ) }>
        <li>
            <a href='readpmsg.php?start=<{$previous}>&amp;total_messages=<{$total_messages}>' title='<{translate key="PREVIOUS_MESSAGE"}>'>
                <{translate key='PREVIOUS_MESSAGE'}>
            </a>
        </li>
    <{else}>
        <li class="disabled">
            <a href="#"><{translate key='PREVIOUS_MESSAGE'}></a>
        </li>
    <{/if}>
    <{if ( $next < $total_messages ) }>
        <li>
            <a href='readpmsg.php?start=<{$next}>&amp;total_messages=<{$total_messages}>' title='<{translate key="NEXT_MESSAGE"}>'>
                <{translate key="NEXT_MESSAGE"}>
            </a>
        </li>
    <{else}>
        <li class="disabled">
            <a href="#"><{translate key="NEXT_MESSAGE"}></a>
        </li>
    <{/if}>
</ul>
<{/if}>