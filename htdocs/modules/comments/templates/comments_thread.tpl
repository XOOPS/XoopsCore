<{section name=i loop=$comments}>
<br/>
<table cellspacing="1" class="outer">
    <tr>
        <th class="width20"><{$comments_lang_poster}></th>
        <th><{$comments_lang_thread}></th>
    </tr>
    <{include file="module:comments/comment.tpl" comment=$comments[i]}>
</table>

<{if $show_threadnav == true}>
    <div class="txtleft marg3 pad5">
        <a href="<{$comment_url}>" title="<{$comments_lang_top}>"><{$comments_lang_top}></a> | <a
            href="<{$comment_url}>&amp;com_id=<{$comments[i].pid}>&amp;com_rootid=<{$comments[i].rootid}>#comment<{$comments[i].pid}>"><{$comments_lang_parent}></a>
    </div>
<{/if}>

<{if $comments[i].show_replies == true}>
    <!-- start comment tree -->
    <br/>
    <table cellspacing="1" class="outer">
        <tr>
            <th class="width50"><{$comments_lang_subject}></th>
            <th class="width20 txtcenter"><{$comments_lang_poster}></th>
            <th class="txtright"><{$comments_lang_posted}></th>
        </tr>
        <{foreach item=reply from=$comments[i].replies}>
        <tr>
            <td class="even"><{$reply.prefix}> <a
                    href="<{$comment_url}>&amp;com_id=<{$reply.id}>&amp;com_rootid=<{$reply.root_id}>"
                    title=""><{$reply.title}></a></td>
            <td class="odd txtcenter"><{$reply.poster.uname}></td>
            <td class="even right"><{$reply.date_posted}></td>
        </tr>
        <{/foreach}>
    </table>
    <!-- end comment tree -->
    <{/if}>
<{/section}>