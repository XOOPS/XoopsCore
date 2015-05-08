<div class="xo-block-newuser">
    <table class="table table-striped table-condensed">
        <tbody>
            <{foreach item=user from=$block.users}>
            <tr>
                <td class="span2 txt-centered">
                    <{if $user.avatar != ""}>
                    <img class="thumbnail" src="<{$user.avatar}>" alt="<{$user.name}>" />
                    <{/if}>
                    <a href="<{$xoops_url}>/userinfo.php?uid=<{$user.id}>" title="<{$user.name}>"><{$user.name}></a>
                </td>
                <td class="txt-centered span1"><{$user.joindate}></td>
            </tr>
            <{/foreach}>
        </tbody>
    </table>
</div>
