<{if $isAdmin|default:false}>
<div class="publisher_adminlinks"><{$publisher_adminpage}></div>
<{/if}>

<{if $canComment|default:false}>
<{include file='module:comments/comments.tpl'}>
<{/if}>

<{if $rssfeed_link|default:false}>
<div id="publisher_rpublisher_feed"><{$rssfeed_link}></div><{/if}>

<{include file='module:notifications/select.tpl'}>
