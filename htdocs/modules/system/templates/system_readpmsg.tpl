<h4 class="txtcenter">{translate key='PRIVATE_MESSAGES'}</h4>
<br />
<ul class="breadcrumb">
    <li><a href="userinfo.php?uid={$xoops_userid}">{translate key='PROFILE'}</a></span></li>
    <li><a href='viewpmsg.php'>{translate key="INBOX"}</a></li>
    <li class="active">{$subject}</li>
</ul>
<br />

{$error_msg|default:false}
{if $read|default:false}
<form name='prvmsg' method='post' action='readpmsg.php'>
    <div class="row">
        <div class="col-md-4">
            {translate key="FROM"}
            {if $poster|default:false}
                <a href='{$xoops_url}/userinfo.php?uid={$poster->getVar("uid")}'>{$poster->getVar("uname")}</a><br />
                {if ( $poster_avatar != "" ) }
                    <img src="{$poster_avatar}" alt='' /><br />
                {/if}
                {if ( $poster->getVar("user_from") != "" ) }
                    {translate key='C_FROM'}{$poster->getVar("user_from")}<br /><br />
                {/if}
                {if ( $poster->isOnline() ) }
                    <span class='bold red'>{translate key='ONLINE'}</span><br /><br />
                {/if}
            {else}
                {$anonymous}
            {/if}
        </div>
        <div class="col-md-8">
            <h4>{$subject}</h4>
            <div class="text-muted text-right"><small>{translate key='C_SENT'}&nbsp;{$msg_time|datetime}</small></div>
            {$msg_text}
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <br>
            {if $poster|default:false}
                <input type='button' class='btn btn-default btn-sm' onclick='javascript:openWithSelfMain("pmlite.php?reply=1&amp;msg_id={$msg_id}", "pmlite",750,720);' value='{translate key="A_REPLY"}' />
            {/if}
            <input type='submit' class='btn btn-danger btn-sm' name='delete_messages' value='{translate key="A_DELETE"}' />
            <input type='hidden' name='op' value='delete'>
            <input type='hidden' name='msg_id' value='{$msg_id}'>
            {$token}
        </div>
    </div>
</form>
<div class="row">
<ul class="pager">
    {if ( $previous >= 0 ) }
        <li>
            <a href='readpmsg.php?start={$previous}&amp;total_messages={$total_messages}' title='{translate key="PREVIOUS_MESSAGE"}'>
                {translate key='PREVIOUS_MESSAGE'}
            </a>
        </li>
    {else}
        <li class="disabled">
            <a href="#">{translate key='PREVIOUS_MESSAGE'}</a>
        </li>
    {/if}
    {if ( $next < $total_messages ) }
        <li>
            <a href='readpmsg.php?start={$next}&amp;total_messages={$total_messages}' title='{translate key="NEXT_MESSAGE"}'>
                {translate key="NEXT_MESSAGE"}
            </a>
        </li>
    {else}
        <li class="disabled">
            <a href="#">{translate key="NEXT_MESSAGE"}</a>
        </li>
    {/if}
</ul>
</div>
{/if}
