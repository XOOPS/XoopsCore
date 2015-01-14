<div class="xo-block-siteinfo">
    <ul class="nav nav-list">
        <{if $block.showgroups == true}>
            <!-- start group loop -->
            <{foreach item=group from=$block.groups}>
            <li class="nav-header">
                <{$group.name}>
            </li>
            <!-- start group member loop -->
            <{foreach item=user from=$group.users}>
            <li>
                <img class="thumbnail pull-left" src="<{$user.avatar}>" alt="<{$user.name}>">
                <div class="pull-left">
                    <a href="<{$xoops_url}>/userinfo.php?uid=<{$user.id}>" title="<{$user.name}>">
                        <i class="icon-user"></i>
                        <{$user.name}>
                    </a>
                    <{if $user.pm_link}>
                        <a class="pull-left" href="javascript:openWithSelfMain('<{$user.pm_link}>','pmlite',500,450)">
                        <i class="ico-email"></i>
                        </a>
                    <{/if}>
                    <{if $user.msg_link}>
                        <a class="pull-left" href="mailto:<{$user.msg_link}>">
                            <i class="ico-email"></i>
                        </a>
                    <{/if}>
                </div>
                <div class="clear"></div>
            </li>
            <{/foreach}>
            <!-- end group member loop -->
        <{/foreach}>
        <!-- end group loop -->
        <{/if}>
    </ul>
</div>
<br />
<div class="pagination-centered">
    <img src="<{$block.logourl}>" alt=""><br /><{$block.recommendlink}>
</div>