<div class="xo-block-login">
    <form class="form" action="<{xoAppUrl 'user.php'}>" method="post">
        <div class="control-group">
            <label class="control-label" for="xo-login-uname"><{$block.lang_username}></label>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on">
                        <i class="icon-user"></i>
                    </span><input class="span2" type="text" name="uname" id="xo-login-uname" value="<{$block.unamevalue}>" placeholder="Login">
                </div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="xo-login-pass"><{$block.lang_password}></label>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on">
                        <i class="icon-cog"></i>
                    </span><input class="span2" type="password" name="pass" id="xo-login-pass">
                </div>
            </div>
        </div>
        <{if isset($block.lang_rememberme)}>
        <label class="checkbox">
            <input type="checkbox" name="rememberme" value="On" class="formButton" checked><{$block.lang_rememberme}>
        </label>
        <{/if}>
        <br/>
        <input type="hidden" name="xoops_redirect" value="<{$xoops_requesturi}>"/>
        <input type="hidden" name="op" value="login"/>
        <div class="pagination-centered">
            <button class="btn btn-primary" type="submit">
                <i class="icon-white icon-user"></i>
                <{$block.lang_login}>
            </button>
            <hr />
            <a class="btn btn-mini pull-left" href="<{xoAppUrl 'user.php#lost'}>" title="<{$block.lang_lostpass}>"><{$block.lang_lostpass}></a>
            <a class="btn btn-mini btn-info pull-right" href="<{xoAppUrl 'register.php'}>" title="<{$block.lang_registernow}>"><{$block.lang_registernow}></a>
            <div class="clear"></div>
        </div>
        <{$block.sslloginlink|default:''}>
    </form>
</div>