<{include file="module:publisher/publisher_header.tpl" item=$item|default:false}>

<{if $op|default:'' == 'preview'}>
<br/>
<{include file="module:publisher/publisher_singleitem.tpl" item=$item|default:false}>
<{/if}>

<div class="publisher_infotitle"><{$lang_intro_title|default:''}></div>
<div class="publisher_infotext"><{$lang_intro_text|default:''}></div>
<br/>
<{$form.javascript}>
<form name="<{$form.name}>" action="<{$form.action}>" method="<{$form.method}>" <{$form.extra}>>
<table class="outer" cellspacing="1">
    <!-- start of form elements loop -->
    <{foreach item=element from=$form.elements}>
    <{if $element.hidden != true}>
        <tr>
            <td class="head"><{$element.caption}>
                <{if $element.description|default:false}>
                    <div style="font-weight: normal"><{$element.description}></div>
                    <{/if}>
            </td>
            <td class="<{cycle values="even,odd"}>"><{$element.body}></td>
        </tr>
        <{else}>
        <{$element.body}>
        <{/if}>
    <{/foreach}>
    <!-- end of form elements loop -->
</table>
</form>

<{if $isAdmin|default:false}>
<div class="publisher_adminlinks"><{$publisher_adminpage}></div>
<{/if}>
