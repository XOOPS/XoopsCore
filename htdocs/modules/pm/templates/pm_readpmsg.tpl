<div>
    <h4><{translate key='PRIVATE_MESSAGES'}></h4>
</div><br />
<{if $op==out}>
    <a href='viewpmsg.php?op=out' title='<{$smarty.const._PM_OUTBOX}>'><{$smarty.const._PM_OUTBOX}></a>&nbsp;
<{elseif $op == "save"}>
    <a href='viewpmsg.php?op=save' title='<{$smarty.const._PM_SAVEBOX}>'><{$smarty.const._PM_SAVEBOX}></a>&nbsp;
<{else}>
    <a href='viewpmsg.php?op=in' title='<{translate key="INBOX"}>'><{translate key="INBOX"}></a>&nbsp;
<{/if}>

<{if $message|default:false}>
    <span class='bold'>&raquo;&raquo;</span>&nbsp;<{$message.subject}><br />
    <form name="<{$pmform.name}>" id="<{$pmform.name}>" action="<{$pmform.action}>" method="<{$pmform.method}>" <{$pmform.extra}> >
        <table class='outer bnone width100'>
            <tr>
                <th colspan='2'><{if $op==out}><{translate key='C_TO'}><{else}><{translate key="C_FROM"}><{/if}></th>
            </tr>
            <tr class='even'>
                <td class='aligntop'>
                    <{if ( $poster != false ) }>
                        <a href='<{$xoops_url}>/userinfo.php?uid=<{$poster->getVar("uid")}>'><{$poster->getVar("uname")}></a><br />
                        <{if ( $poster_avatar != "" ) }>
                            <img src='<{$poster_avatar}>' alt='' /><br />
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
                    <{if $message.msg_image != ""}>
                        <img src='<{$xoops_url}>/images/subject/<{$message.msg_image}>' alt='' />
                    <{/if}>
                    <{translate key='C_SENT'}><{$message.msg_time}><br />
                    <hr />
                    <strong><{$message.subject}></strong><br />
                    <br />
                    <{$message.msg_text}><br />
                    <br />
                </td>
            </tr>
            <tr class='foot'>
                <td class='width20 txtleft' colspan='2'>
                    <{foreach item=element from=$pmform.elements}>
                        <{$element.body}>
                    <{/foreach}>
                </td>
            </tr>
            <tr>
                <td class='txtright' colspan='2'>
                    <ul class="pager">
                        <{if ( $previous >= 0 ) }>
                            <li>
                                <a href='readpmsg.php?start=<{$previous}>&amp;total_messages=<{$total_messages}>&amp;op=<{$op}>' title='<{translate key="PREVIOUS_MESSAGE"}>'>
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
                                <a href='readpmsg.php?start=<{$next}>&amp;total_messages=<{$total_messages}>&amp;op=<{$op}>' title='<{translate key="NEXT_MESSAGE"}>'>
                                    <{translate key="NEXT_MESSAGE"}>
                                </a>
                            </li>
                        <{else}>
                            <li class="disabled">
                                <a href="#"><{translate key="NEXT_MESSAGE"}></a>
                            </li>
                        <{/if}>
                    </ul>
                </td>
            </tr>
        </table>
    </form>
<{else}>
    <br /><br /><{translate key='E_YOU_DO_NOT_HAVE_ANY_PRIVATE_MESSAGE'}>
<{/if}>