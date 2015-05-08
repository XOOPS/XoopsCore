<table class="outer" cellpadding="5" cellspacing="1">
    <tr>
        <th class="width20"><{$comments_lang_poster}></th>
        <th><{$comments_lang_thread}></th>
    </tr>
    <{foreach item=comment from=$comments|default:[]}>
    <{include file="module:comments/comment.tpl" comment=$comment}>
    <{/foreach}>
</table>