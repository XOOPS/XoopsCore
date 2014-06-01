<fieldset class="pad10">
  <legend class="bold"><{$lang_login}></legend>
  <form action="user.php" method="post">
    <{$lang_username}> <input type="text" name="uname" size="26" maxlength="25" value="<{$usercookie}>" /><br /><br />
    <{$lang_password}> <input type="password" name="pass" size="21" maxlength="32" /><br /><br />
    <{if isset($lang_rememberme)}>
        <input type="checkbox" name="rememberme" value="On" checked /> <{$lang_rememberme}><br /><br />
    <{/if}>

    <input type="hidden" name="op" value="login" />
    <input type="hidden" name="xoops_redirect" value="<{$redirect_page}>" />
    <input type="submit" value="<{$lang_login}>" />
  </form>
  <br />
  <a name="lost"></a>
  <div><{translate key='Q_NOT_REGISTERED'}>&nbsp;<{translate key='CLICK_HERE_TO_REGISTER'}><br /></div>
</fieldset>

<br />

<fieldset class="pad10">
  <legend class="bold"><{$lang_lostpassword}></legend>
  <div><br /><{$lang_noproblem}></div>
  <form action="lostpass.php" method="post">
    <{$lang_youremail}> <input type="text" name="email" size="26" maxlength="60" />&nbsp;&nbsp;<input type="hidden" name="op" value="mailpasswd" /><input type="hidden" name="t" value="<{$mailpasswd_token}>" /><input type="submit" value="<{$lang_sendpassword}>" />
  </form>
</fieldset>