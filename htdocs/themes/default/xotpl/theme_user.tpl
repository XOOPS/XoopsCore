<div class="pull-right">
    <{if !$xoops_isuser}>
    <a class="btn btn-primary" href="<{xoAppUrl user.php}>">
        <i class="icon-white icon-user"></i>
        <{translate key='A_LOGIN'}>
    </a>
    <a class="btn btn-info" href="<{xoAppUrl register.php}>">
        <i class="icon-white icon-leaf"></i>
        <{translate key='A_REGISTER'}>
    </a>
    <{else}>
    <ul class="nav pull-right">
        <li class="divider-vertical"></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="ico-lightning"></i>&nbsp;<{translate key='QUICK_ACCESS'}><i class="caret"></i></a>
            <ul class="dropdown-menu">
                <{if $xoops_isadmin}>
                <li>
                    <a href="<{xoAppUrl admin.php}>" title="<{translate key='ADMINISTRATION'}>" rel="external">
                        <i class="icon-wrench"></i>
                        <{translate key='ADMINISTRATION'}>
                    </a>
                </li>
                <{/if}>
                <li>
                    <a href="<{xoAppUrl user.php}>" title="<{translate key='VIEW_ACCOUNT'}>">
                        <i class="icon-user"></i>
                        <{translate key='VIEW_ACCOUNT'}>
                    </a>
                </li>
                <li>
                    <a href="<{xoAppUrl edituser.php}>" title="<{translate key='EDIT_ACCOUNT'}>">
                        <i class="icon-edit"></i>
                        <{translate key='EDIT_ACCOUNT'}>
                    </a>
                </li>
                <li>
                    <a href="<{xoAppUrl /notifications.php}>" title="<{translate key='NOTIFICATIONS'}>">
                        <i class="icon-comment"></i>
                        <{translate key='NOTIFICATIONS'}>
                    </a>
                </li>
                <{xoInboxCount assign=pm_count}>
                <{if $pm_count}>
                <li>
                    <a href="<{xoAppUrl viewpmsg.php}>" title="<{translate key='INBOX'}>">
                        <i class="icon-envelope"></i>
                        <{translate key='INBOX'}> (<strong><{$pm_count}></strong>)
                    </a>
                </li>
                <{else}>
                <li>
                    <a href="<{xoAppUrl viewpmsg.php}>" title="<{translate key='INBOX'}>">
                        <i class="icon-envelope"></i>
                        <{translate key='INBOX'}>
                    </a>
                </li>
                <{/if}>
                <li class="divider"></li>
                <li>
                    <a href="<{xoAppUrl user.php?op=logout}>" title="<{translate key='A_LOGOUT'}>">
                        <i class="icon-off"></i>
                        <{translate key='A_LOGOUT'}>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <script type="text/javascript">
        $('.dropdown-toggle').dropdown();
    </script>
    <{/if}>
</div>
