<div>
    <h4>{translate key='PRIVATE_MESSAGES'}</h4>
</div>
{$breadcrumbs|default:''}
{if $msg|default:false}
    <div class="confirmMsg">{$msg}</div>
{/if}
{if $errormsg|default:false}
    <div class="errorMsg">{$errormsg}</div>
{/if}

<div class="btn-toolbar pull-right" role="toolbar">
    {$folders|default:''}
</div>

<form name="{$pmform.name}" id="{$pmform.name}" action="{$pmform.action}" method="{$pmform.method}" {$pmform.extra} >
    <table class="table table-striped table-condensed" cellspacing='1' cellpadding='4'>

        <tr class='txtcenter alignmiddle'>
            <th><input name='allbox' id='allbox' onclick='xoopsCheckAll("{$pmform.name}", "allbox");' type='checkbox' value='Check All' /></th>
            <th class="txtcenter"><span class="glyphicon glyphicon-download-alt"></span></th>
            {if $op == "out"}
                <th>{translate key='C_TO'}</th>
            {else}
                <th>{translate key='C_FROM'}</th>
            {/if}
            <th>{translate key='SUBJECT'}</th>
            <th class='txtcenter'>{translate key='DATE'}</th>
        </tr>

        {if $total_messages == 0}
            <tr>
                <td class='even txtcenter' colspan='6'>{$smarty.const._PM_FOLDER_EMPTY}</td>
            </tr>
        {/if}
        {foreach item=message from=$messages|default:[]}
            <tr{if $message.read_msg != 1} class="info"{/if} >
                <td class='aligntop txtcenter'>
                    <input type='checkbox' id='msg_id_{$message.msg_id}' name='msg_id[]' value='{$message.msg_id}' />
                </td>
                {if $message.read_msg == 1}
                    <td class='aligntop txtcenter'><img src='{xoModuleIcons16 'mail_read.png'}' alt='{translate key="READ"}' title='{translate key="READ"}'/></td>
                {else}
                    <td class='aligntop txtcenter'><img src='{xoModuleIcons16 'mail_notread.png'}' alt='{translate key="NOT_READ"}' title='{translate key="NOT_READ"}'/></td>
                {/if}
                <td class='alignmiddle'>
                    {if $message.postername != ""}
                        <a href='{$xoops_url}/userinfo.php?uid={$message.posteruid}' title=''>{$message.postername}</a>
                    {else}
                        {$anonymous}
                    {/if}
                </td>
                <td class='alignmiddle'>
                    <a href='readpmsg.php?msg_id={$message.msg_id}&amp;start={$message.msg_no}&amp;total_messages={$total_messages}&amp;op={$op}' title=''>
                        {$message.subject}
                    </a>
                </td>
                <td class='alignmiddle txtcenter'>
                    {$message.msg_time|datetime:'elapse'}
                </td>
            </tr>
        {/foreach}
        <tr class='bg2 txtleft'>
            <td class='txtleft' colspan='6'>
                {$pmform.elements.send.body}
                {if $display}
                    &nbsp;{$pmform.elements.move_messages.body}
                    &nbsp;{$pmform.elements.delete_messages.body}
                    &nbsp;{$pmform.elements.empty_messages.body}
                {/if}
                {foreach item=element from=$pmform.elements}
                    {if $element.hidden == 1}
                        {$element.body}
                    {/if}
                {/foreach}
            </td>
        </tr>
    </table>
</form>
{if $pagenav|default:false}
<div class="pull-right">
{$pagenav}
</div>
{/if}
