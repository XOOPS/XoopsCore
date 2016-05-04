<div class="xo-block-login">
    <form class="form" action="{xoAppUrl 'user.php'}" method="post">
        <div class="form-group">
            <label class="control-label" for="xo-login-uname">{$block.lang_username}</label>
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                <input class="form-control" type="text" name="uname" id="xo-login-uname" value="{$block.unamevalue}" placeholder="Login">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label" for="xo-login-pass">{$block.lang_password}</label>
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                <input class="form-control" type="password" name="pass" id="xo-login-pass">
            </div>
        </div>
        {if isset($block.lang_rememberme)}
        <label class="input-group">
            <input type="checkbox" name="rememberme" value="On" class="formButton" checked>{$block.lang_rememberme}
        </label>
        {/if}
        <br/>
        <input type="hidden" name="xoops_redirect" value="{$xoops_requesturi}"/>
        <input type="hidden" name="op" value="login"/>
        <div class="pagination-centered">
            <button class="btn btn-primary" type="submit">
                <i class="icon-white icon-user"></i>
                {$block.lang_login}
            </button>
            <hr />
            <a class="btn btn-sm pull-left" href="{xoAppUrl 'user.php#lost'}" title="{$block.lang_lostpass}">{$block.lang_lostpass}</a>
            <a class="btn btn-sm btn-info pull-right" href="{xoAppUrl 'register.php'}" title="{$block.lang_registernow}">{$block.lang_registernow}</a>
            <div class="clear"></div>
        </div>
        {$block.sslloginlink|default:''}
    </form>
</div>
