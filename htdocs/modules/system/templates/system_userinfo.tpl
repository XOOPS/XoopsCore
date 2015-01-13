<{if $user_ownpage|default:false}>
<div class="btn-group">
    <a class="btn" href="<{xoAppUrl 'edituser.php'}>">
        <i class="icon-edit"></i>
        <{$lang_editprofile}>
    </a>
    <{if $btn|default:false}>
    <{foreach item=button from=$btn}>
    <{assign var="absurl" value=$button.link|strpos:"//"}>
    <{if $absurl|default:false}>
    <a class="btn" href="<{$button.link}>">
    <{else}>
    <a class="btn" href="<{xoAppUrl}><{$button.link}>">
    <{/if}>
        <i class="<{$button.icon}>"></i>
        <{$btn.title|default:''}>
    </a>
    <{/foreach}>
    <{/if}>
    <a class="btn" href="<{xoAppUrl 'viewpmsg.php'}>">
        <i class="icon-envelope"></i>
        <{$lang_inbox}>
    </a>
    <{if $user_candelete|default:false}>
    <a class="btn btn-danger" href="<{xoAppUrl 'user.php?op=delete'}>">
        <i class="icon-trash icon-white"></i>
        <{$lang_deleteaccount}>
    </a>
    <{/if}>
    <a class="btn" href="<{xoAppUrl 'user.php?op=logout'}>">
        <i class="icon-off"></i>
        <{$lang_logout}>
    </a>
</div>
<{elseif $xoops_isadmin|default:false}>
<div class="btn-group">
    <a class="btn" href="<{$xoops_url}>/modules/system/admin.php?fct=users&amp;uid=<{$user_uid}>&amp;op=modifyUser">
        <i class="icon-edit"></i>
        <{$lang_editprofile}>
    </a>
    <a class="btn btn-danger" href="<{$xoops_url}>/modules/system/admin.php?fct=users&amp;op=delUser&amp;uid=<{$user_uid}>">
        <i class="icon-trash icon-white"></i>
        <{$lang_deleteaccount}>
    </a>
</div>
<{/if}>
<div class="spacer-large"></div>
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab"><{$lang_allaboutuser}></a></li>
        <li><a href="#tab2" data-toggle="tab"><{$lang_statistics}></a></li>
        <li><a href="#tab3" data-toggle="tab"><{$lang_posts}></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab1">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th colspan="2"><{$lang_allaboutuser}></th>
                    </tr>
                </thead>
                <tbody>
                    <{if $user_avatarurl|default:false}>
                    <tr>
                        <td><{$lang_avatar}></td>
                        <td><img src="<{$user_avatarurl}>" alt="Avatar" /></td>
                    </tr>
                    <{/if}>
                    <{if $user_realname|default:false}>
                    <tr>
                        <td><{$lang_realname}></td>
                        <td><{$user_realname}></td>
                    </tr>
                    <{/if}>
                    <{if $user_websiteurl|default:false}>
                    <tr>
                        <td><{$lang_website}></td>
                        <td><{$user_websiteurl}></td>
                    </tr>

                    <{/if}>
                    <{if $user_email|default:false}>
                    <tr>
                        <td><{$lang_email}></td>
                        <td><{$user_email}></td>
                    </tr>
                    <{/if}>
                    <{if !$user_ownpage == true}>
                    <tr>
                        <td><{$lang_privmsg}></td>
                        <td><{$user_pmlink}></td>
                    </tr>
                    <{/if}>
                    <{if $user_icq|default:false}>
                    <tr>
                        <td><{$lang_icq}></td>
                        <td><{$user_icq}></td>
                    </tr>
                    <{/if}>
                    <{if $user_aim|default:false}>
                    <tr>
                        <td><{$lang_aim}></td>
                        <td><{$user_aim}></td>
                    </tr>
                    <{/if}>
                    <{if $user_yim|default:false}>
                    <tr>
                        <td><{$lang_yim}></td>
                        <td><{$user_yim}></td>
                    </tr>
                    <{/if}>
                    <{if $user_msnm|default:false}>
                    <tr>
                        <td><{$lang_msnm}></td>
                        <td><{$user_msnm}></td>
                    </tr>
                    <{/if}>
                    <{if $user_location|default:false}>
                    <tr>
                        <td><{$lang_location}></td>
                        <td><{$user_location}></td>
                    </tr>
                    <{/if}>
                    <{if $user_occupation|default:false}>
                    <tr>
                        <td><{$lang_occupation}></td>
                        <td><{$user_occupation}></td>
                    </tr>
                    <{/if}>
                    <{if $user_interest|default:false}>
                    <tr>
                        <td><{$lang_interest}></td>
                        <td><{$user_interest}></td>
                    </tr>
                    <{/if}>
                    <{if $user_extrainfo|default:false}>
                    <tr>
                        <td><{$lang_extrainfo}></td>
                        <td><{$user_extrainfo}></td>
                    </tr>
                    <{/if}>
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="tab2">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th colspan="2"><{$lang_statistics}></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><{$lang_membersince}></td>
                        <td><{$user_joindate}></td>
                    </tr>
                    <{if $user_ranktitle}>
                        <tr>
                            <td><{$lang_rank}></td>
                            <td><{$user_rankimage}><br /><{$user_ranktitle}></td>
                        </tr>
                    <{/if}>
                    <tr>
                        <td><{$lang_posts}></td>
                        <td><{$user_posts}></td>
                    </tr>
                    <tr>
                        <td><{$lang_lastlogin}></td>
                        <td><{$user_lastlogin}></td>
                    </tr>
                </tbody>
            </table>
            <{if $user_signature}>
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    <th colspan="2"><{$lang_signature}></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><{$user_signature}></td>
                </tr>
                </tbody>
            </table>
            <{/if}>
        </div>

        <{if $modules|default:false}>
            <div class="tab-pane" id="tab3">
                <{include file="module:search/search.tpl"}>
            </div>
        <{/if}>
    </div>
</div>
