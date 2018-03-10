<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="module:system/system_form.tpl"}>
<form class="form-inline" name="filter" id="filter" action="center.php" method="get">
    <fieldset>
        <select name='num' onchange='submit();'><{$num_options}></select>
        <input class="btn" type='submit' value='<{translate key="A_SUBMIT"}>'>
    </fieldset>
</form>

<form name='MainForm' action='center.php' method='post'>
    <{$ticket|default:''}>
    <input type='hidden' name='action' value=''/>
    <table class="outer">
        <thead>
        <tr>
            <th class="txtcenter width2">
                <input type='checkbox' name='dummy' onclick="with(document.MainForm)
                <{literal}>
                        {for(i=0; i<length; i++)
                        {if(elements[i].type=='checkbox')
                        {elements[i].checked=this.checked;}
                        }
                        }
                <{/literal}>"/>
            </th>
            <th class="txtcenter width10"><{$smarty.const._AM_TH_DATETIME}></th>
            <th class="txtcenter width10"><{$smarty.const._AM_TH_USER}></th>
            <th class="txtcenter width10">
                <{$smarty.const._AM_TH_IP}>
                <br/>
                <{$smarty.const._AM_TH_AGENT}>
            </th>
            <th class="txtcenter width10"><{$smarty.const._AM_TH_TYPE}></th>
            <th class="txtcenter"><{$smarty.const._AM_TH_DESCRIPTION}></th>
        </tr>
        </thead>
        <tbody>
        <{if $log|default:false}>
            <{foreach item=logitem from=$log}>
                <tr class="<{cycle values='even,odd'}>">
                    <td class="txtcenter"><input type='checkbox' name='ids[]' value='<{$file_item.lid}>'/></td>
                    <td class="txtcenter"><{$file_item.date}></td>
                    <td class="txtcenter"><{$file_item.uname}></td>
                    <td class="txtleft"><{$file_item.ip}>
                        <{$file_item.ip}>
                        <br/>
                        <{$file_item.agent_desc}>
                    </td>
                    <td class="txtcenter"><{$file_item.type}></td>
                    <td class="txtleft"><{$file_item.description}></td>
                </tr>
            <{/foreach}>
        <{/if}>
        <tr class="odd">
            <td colspan="6" class="txtleft">
                <{$smarty.const._AM_LABEL_REMOVE}>
                <input class="btn" type='button' value='<{$smarty.const._AM_BUTTON_REMOVE}>' onclick='if(confirm("<{$smarty.const._AM_JS_REMOVECONFIRM}>")) <{literal}>{document.MainForm.action.value="delete"; submit();} <{/literal}>'/>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="floatright">
        <{$nav_html}>
    </div>
    <br/>
    <br/>
    <div class="floatright">
        <{$smarty.const._AM_LABEL_COMPACTLOG}>&nbsp;<input class="btn" type='button' value='<{$smarty.const._AM_BUTTON_COMPACTLOG}>' onclick='if(confirm("<{$smarty.const._AM_JS_COMPACTLOGCONFIRM}>")) <{literal}>{document.MainForm.action.value="compactlog"; submit();} <{/literal}>'/>
        &nbsp;
        <{$smarty.const._AM_LABEL_REMOVEALL}>&nbsp;<input class="btn" type='button' value='<{$smarty.const._AM_BUTTON_REMOVEALL}>' onclick='if(confirm("<{$smarty.const._AM_JS_REMOVEALLCONFIRM}>")) <{literal}>{document.MainForm.action.value="deleteall"; submit();} <{/literal}>'/>
    </div>
</form>
