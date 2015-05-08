<form class="form-horizontal" action="user.php" method="post">
    <fieldset>
        <legend class="bold"><{$lang_login}></legend>
        <div class="control-group">
            <label for="xo-sys-uname" class="control-label"><{$lang_username}><span class="caption-required">*</span></label>
            <div class="controls">
                <input type="text" name="uname" id="xo-sys-uname" class="span2" value="<{$usercookie}>" required="">
            </div>
        </div>
        <div class="control-group">
            <label for="xo-sys-pass" class="control-label"><{$lang_password}><span class="caption-required">*</span></label>
            <div class="controls">
                <input type="password" name="pass" id="xo-sys-pass" class="span2" required="">
            </div>
        </div>
        <{if isset($lang_rememberme)}>
        <div class="control-group">
            <div class="controls">
                <label class="checkbox">
                    <input type="checkbox" name="rememberme" value="On" checked>
                    <{$lang_rememberme}>
                </label>
            </div>
        </div>
        <{/if}>
        <input type="hidden" name="op" value="login">
        <input type="hidden" name="xoops_redirect" value="<{$redirect_page}>">
        <div class="form-actions">
            <button class="btn btn-primary" type="submit"><{$lang_login}></button>
        </div>
    </fieldset>
</form>
<p><{translate key='Q_NOT_REGISTERED'}>&nbsp;<{translate key='CLICK_HERE_TO_REGISTER'}></p>
<fieldset name="lost">
    <legend class="bold"><{$lang_lostpassword}></legend>
    <form class="form-horizontal" action="lostpass.php" method="post">
        <p><{$lang_noproblem}></p>
        <div class="control-group">
            <label for="xo-sys-uname" class="control-label"><{$lang_youremail}><span class="caption-required">*</span></label>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on">@</span><input type="text" name="email" id="xo-sys-email" class="span3" value="<{$usercookie}>" required="">
                </div>
            </div>
        </div>
        <input type="hidden" name="op" value="mailpasswd">
        <input type="hidden" name="t" value="<{$mailpasswd_token}>">
        <div class="form-actions">
            <button class="btn btn-primary" type="submit"><{$lang_sendpassword}></button>
        </div>
    </form>
</fieldset>