<{section name=i loop=$comments}>
<br/>
<table cellspacing="1" class="outer">
    <tr>
        <th class="width20"><{$comments_lang_poster}></th>
        <th><{$comments_lang_thread}></th>
    </tr>
    <{include file="module:comments/comment.tpl" comment=$comments[i]}>
</table>

<!-- start comment replies -->
<{foreach item=reply from=$comments[i].replies}>
    <br/>
    <table class="bnone collapse">
        <tr>
            <td width="<{$reply.prefix}>"></td>
            <td>
                <table class="outer" cellspacing="1">
                    <tr>
                        <th class="width20"><{$comments_lang_poster}></th>
                        <th><{$comments_lang_thread}></th>
                    </tr>
                    <{include file="module:comments/comment.tpl" comment=$reply}>
                </table>
            </td>
        </tr>
    </table>
    <{/foreach}>
<!-- end comment tree -->
<{/section}>