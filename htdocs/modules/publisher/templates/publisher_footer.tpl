<{if $isAdmin == 1}>
<div class="publisher_adminlinks"><{$publisher_adminpage}></div>
<{/if}>

<{if $canComment == 1}>
<{include file='module:comments|comments.html'}>
<{/if}>

<{if $rssfeed_link != ""}>
<div id="publisher_rpublisher_feed"><{$rssfeed_link}></div><{/if}>

<{include file='module:notifications|select.html'}>
