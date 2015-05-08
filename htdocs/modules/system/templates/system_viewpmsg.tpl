<h4 class="txtcenter"><{translate key='PRIVATE_MESSAGES'}></h4>
<br />
<ul class="breadcrumb">
    <li><a href="userinfo.php?uid=<{$xoops_userid}>"><{translate key='PROFILE'}></a> <span class="divider">/</span></li>
    <li class="active"><{translate key="INBOX"}></li>
</ul>
<br />
<{if $display|default:false}>
<form name='prvmsg' method='post' action='viewpmsg.php'>
    <table class="table table-striped table-bordered table-condensed">
        <tr>
            <th class='width2'>
                <input name='allbox' id='allbox' onclick='xoopsCheckAll("prvmsg", "allbox");' type='checkbox' value='Check All' />
            </th>
            <th class='width5' style="text-align: center;">
                <img class='bnone' src='<{xoAppUrl 'images/download.gif'}>' alt=''/>
            </th>
            <th class='width5'>&nbsp;</th>
            <th class='width10'>
                <{translate key='FROM'}>
            </th>
            <th>
                <{translate key='SUBJECT'}>
            </th>
            <th class='width20' style="text-align: center;">
                <{translate key='DATE'}>
            </th>
        </tr>
        <{if $total_messages == 0}>
        <tr>
            <td colspan='6'>
                <{translate key='E_YOU_DO_NOT_HAVE_ANY_PRIVATE_MESSAGE'}>
            </td>
        </tr>
        <{/if}>
        <{foreach item=message from=$messages|default:[]}>
        <tr>
            <td class='width2'>
                <input type='checkbox' id='msg_id_<{$message.msg_id}>' name='msg_id[]' value='<{$message.msg_id}>' />
            </td>
            <{if $message.read_msg == 1}>
                <td class='width5' style="text-align: center;">
                    <img src="<{xoModuleIcons16 'mail_read.png'}>" alt='<{translate key="READ"}>' title="<{translate key='READ'}>"/>
                </td>
            <{else}>
                <td class='width5' style="text-align: center;">
                    <img src="<{xoModuleIcons16 'mail_notread.png'}>" alt='<{translate key="NOT_READ"}>' title="<{translate key='NOT_READ'}>"/>
                </td>
            <{/if}>
            <td class='width5' style="text-align: center;">
                <{if $message.msg_image != ""}>
                    <img src='<{$xoops_url}>/images/subject/<{$message.msg_image}>' alt='' />
                <{/if}>
            </td>
            <td class='width10'>
                <{if $message.postername != ""}>
                    <a href='<{$xoops_url}>/userinfo.php?uid=<{$message.posteruid}>' title=''><{$message.postername}></a>
                <{else}>
                    <{$anonymous}>
                <{/if}>
            </td>
            <td>
                <a href='readpmsg.php?msg_id=<{$message.msg_id}>&amp;start=<{$message.msg_no}>&amp;total_messages=<{$total_messages}>' title=''>
                    <{$message.subject}>
                </a>
            </td>
            <td class='width20' style="text-align: center;">
                <{$message.msg_time}>
            </td>
        </tr>
        <{/foreach}>
        <{if $total_messages > 0}>
        <tr>
            <td colspan='6'>
                <input type='button' class='btn' onclick='javascript:openWithSelfMain("pmlite.php?send=1", "pmlite",750,720);' value='<{translate key="A_SEND"}>' />
                <input type='submit' class='btn' name='delete_messages' value='<{translate key="A_DELETE"}>' />
                <{$token}>
            </td>
        </tr>
        <{else}>
        <tr>
            <td colspan='6'>
                <input type='button' class='btn' onclick='javascript:openWithSelfMain("pmlite.php?send=1", "pmlite",750,720);' value='<{translate key="A_SEND"}>' />
            </td>
        </tr>
        <{/if}>
    </table>
</form>
<{/if}>