<form action="user.php" method="post">
        <legend class="bold">{$lang_login}</legend>
    <div>
        <div class="form-group">
            <label for="xo-sys-uname">{$lang_username}<span class="caption-required">*</span></label>
            <input type="text" name="uname" id="xo-sys-uname" class="form-control" value="{$usercookie|default:''}" required="">
        </div>
        <div class="form-group">
            <label for="xo-sys-pass">{$lang_password}<span class="caption-required">*</span></label>
            <input type="password" name="pass" id="xo-sys-pass" class="form-control" required="">
        </div>
        {if isset($lang_rememberme)}
        <div class="form-group">
            <label class="input-group">
                <input type="checkbox" name="rememberme" value="On" checked>
                {$lang_rememberme}
            </label>
        </div>
        {/if}
        <input type="hidden" name="op" value="login">
        <input type="hidden" name="xoops_redirect" value="{$redirect_page}">
        <div class="form-actions">
            <button class="btn btn-primary" type="submit">{$lang_login}</button>
        </div>
        </div>
</form>
<p>{translate key='Q_NOT_REGISTERED'}&nbsp;{translate key='CLICK_HERE_TO_REGISTER'}</p>
<fieldset id="lost">
    <legend class="bold">{$lang_lostpassword}</legend>
    <form action="lostpass.php" method="post">
        <p>{$lang_noproblem}</p>
        <div class="form-group">
            <label for="xo-sys-uname">{$lang_youremail}<span class="caption-required">*</span></label>
            <div class="input-group">
                <span class="input-group-addon">@</span>
                <input type="text" name="email" id="xo-sys-email" class="form-control" placeholder="E-mail" value="{$usercookie|default:''}" required>
            </div>
        </div>
        <input type="hidden" name="op" value="mailpasswd">
        <input type="hidden" name="t" value="{$mailpasswd_token}">
        <div class="form-actions">
            <button class="btn btn-primary" type="submit">{$lang_sendpassword}</button>
        </div>
    </form>
</fieldset>
