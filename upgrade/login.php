<?php
defined("XOOPS_ROOT_PATH") OR DIE();

if (empty($_POST['uname']) || empty($_POST['pass'])) {
?>
<h2><?php echo _USER_LOGIN; ?></h2>

<form id="xo-loginupdate" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="xo-formfield required">
            <label><?php echo XoopsLocale::C_USERNAME; ?></label>
            <input type="text" name="uname" size="24" maxlength="25" value="" />
        </div>
        <div class="xo-formfield required">
            <label><?php echo XoopsLocale::C_PASSWORD; ?></label>
            <input type="password" name="pass" size="24" maxlength="32" />
        </div>
        <div class="xo-formbuttons">
            <button type="submit"><?php echo XoopsLocale::A_LOGIN; ?></button>
        </div>
        <input type="hidden" name="xoops_redirect" value="/upgrade/index.php" />
</form>
<?php
} else {
    $xoops = Xoops::getInstance();
    $myts = MyTextsanitizer::getInstance();
    $uname = !isset($_POST['uname']) ? '' : $myts->addSlashes( trim($_POST['uname']) );
    $pass = !isset($_POST['pass']) ? '' : $myts->addSlashes( trim($_POST['pass']) );

    $member_handler = $xoops->getHandlerMember();

    if (!@include_once XOOPS_ROOT_PATH.'/language/' . $upgrade_language . '/auth.php') {
        include_once XOOPS_ROOT_PATH.'/language/english/auth.php';
    }
    $xoopsAuth = Xoops_Auth_Factory::getAuthConnection($uname);
    $user = $xoopsAuth->authenticate($uname, $pass);

    // For XOOPS 2.2*
    if (!is_object($user)) {
        $criteria = new CriteriaCompo(new Criteria('loginname', $uname));
        $criteria->add(new Criteria('pass', md5($pass)));
        list($user) = $member_handler->getUsers($criteria);
    }

    $isAllowed = false;
    if (is_object($user) && $user->getVar('level') > 0) {
        $isAllowed = true;
        if ($xoops->getConfig('closesite') == 1) {
            $groups = $user->getGroups();
            if (in_array(XOOPS_GROUP_ADMIN, $groups) || array_intersect($groups, $xoopsConfig['closesite_okgrp'])) {
                $isAllowed = true;
            }  else {
                $isAllowed = false;
            }
        }
    }
    if ($isAllowed) {
        $user->setVar('last_login', time());
        if (!$member_handler->insertUser($user)) {
        }
        // Regenerate a new session id and destroy old session
        $xoops->sess_handler->regenerate_id(true);
        $_SESSION = array();
        $_SESSION['xoopsUserId'] = $user->getVar('uid');
        $_SESSION['xoopsUserGroups'] = $user->getGroups();
        $user_theme = $user->getVar('theme');
        if (in_array($user_theme, $xoops->getConfig('theme_set_allowed'))) {
            $_SESSION['xoopsUserTheme'] = $user_theme;
        }

        // Set cookie for rememberme
        if ($xoops->getConfig('usercookie')) {
            if ( !empty($_POST["rememberme"]) ) {
                setcookie($xoops->getConfig('usercookie'), $_SESSION['xoopsUserId'], time() + 31536000, '/',  '', 0);
            } else {
                setcookie($xoops->getConfig('usercookie'), 0, -1, '/',  '', 0);
            }
        }
    }

    header("location: " . XOOPS_URL . "/upgrade/index.php");
    exit();
}