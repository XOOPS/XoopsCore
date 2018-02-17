<?php

class upgrade_2014 extends xoopsUpgrade
{
    public $usedFiles = array('mainfile.php');

    public function isApplied()
    {
        return ( /*$this->check_0523patch() &&*/
        $this->check_auth_db());
    }

    public function apply()
    {
        return $this->apply_auth_db();
        /*
        if ( $this->apply_0523patch() ) {
            return $this->apply_auth_db();
        }
        return false;
        */
    }

    public function check_0523patch()
    {
        $lines = file('../mainfile.php');
        foreach ($lines as $line) {
            if (strpos($line, "\$_REQUEST[\$bad_global]") !== false) {
                // Patch found: do not apply again
                return true;
            }
        }
        return false;
    }

    public function apply_0523patch()
    {
        $patchCode = "
    foreach ( array('GLOBALS', '_SESSION', 'HTTP_SESSION_VARS', '_GET', 'HTTP_GET_VARS', '_POST', 'HTTP_POST_VARS', '_COOKIE', 'HTTP_COOKIE_VARS', '_REQUEST', '_SERVER', 'HTTP_SERVER_VARS', '_ENV', 'HTTP_ENV_VARS', '_FILES', 'HTTP_POST_FILES', 'xoopsDB', 'xoopsUser', 'xoopsUserId', 'xoopsUserGroups', 'xoopsUserIsAdmin', 'xoopsConfig', 'xoopsOption', 'xoopsModule', 'xoopsModuleConfig', 'xoopsRequestUri') as \$bad_global ) {
        if ( isset( \$_REQUEST[\$bad_global] ) ) {
            header( 'Location: '.XOOPS_URL.'/' );
            exit();
        }
    }
";
        $manual = "<h2>" . _MANUAL_INSTRUCTIONS . "</h2>\n<p>" . sprintf(_COPY_RED_LINES, "mainfile.php") . "</p>
<pre style='border:1px solid black;width:650px;overflow:auto'><span style='color:#ff0000;font-weight:bold'>$patchCode</span>
    if (!isset(\$xoopsOption['nocommon']) && XOOPS_ROOT_PATH != '') {
        include XOOPS_ROOT_PATH.\"/include/common.php\";
    }
</pre>";
        $lines = file('../mainfile.php');

        $insert = -1;
        $matchProtector = '/modules/protector/include/precheck.inc.php';
        $matchDefault = "\$xoopsOption['nocommon']";

        foreach ($lines as $k => $line) {
            if (strpos($line, "\$_REQUEST[\$bad_global]") !== false) {
                // Patch found: do not apply again
                $insert = -2;
                break;
            }
            if (strpos($line, $matchProtector) || strpos($line, $matchDefault)) {
                $insert = $k;
                break;
            }
        }
        if ($insert == -1) {
            printf(_FAILED_PATCH . "<br>", "mainfile.php");
            echo $manual;
            return false;
        } elseif ($insert != -2) {
            if (!is_writable('../mainfile.php')) {
                echo 'mainfile.php is read-only. Please allow the server to write to this file, or apply the patch manually';
                echo $manual;
                return false;
            } else {
                $fp = fopen('../mainfile.php', 'wt');
                if (!$fp) {
                    echo 'Error opening mainfile.php, please apply the patch manually.';
                    echo $manual;
                    return false;
                } else {
                    $newline = defined(PHP_EOL) ? PHP_EOL : (strpos(php_uname(), 'Windows') ? "\r\n" : "\n");
                    $prepend = implode('', array_slice($lines, 0, $insert));
                    $append = implode('', array_slice($lines, $insert));

                    $content = $prepend . $patchCode . $append;
                    $content = str_replace(array("\r\n", "\n"), $newline, $content);

                    fwrite($fp, $content);
                    fclose($fp);
                    echo "Patch successfully applied";
                }
            }
        }
        return true;
    }

    public function check_auth_db()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $value = getDbValue($db, 'config', 'conf_id', "`conf_name` = 'ldap_provisionning' AND `conf_catid` = " . XOOPS_CONF_AUTH);
        return (bool)$value;
    }

    public function query($sql)
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        if (!($ret = $db->queryF($sql))) {
            echo $db->error();
        }
    }

    public function apply_auth_db()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();

        $cat = getDbValue($db, 'configcategory', 'confcat_id', "`confcat_name` ='_MD_AM_AUTHENTICATION'");
        if ($cat !== false && $cat != XOOPS_CONF_AUTH) {
            // 2.2 downgrade bug: LDAP cat is here but has a catid of 0
            $db->queryF("DELETE FROM " . $db->prefix('configcategory') . " WHERE `confcat_name` ='_MD_AM_AUTHENTICATION' ");
            $db->queryF("DELETE FROM " . $db->prefix('config') . " WHERE `conf_modid`=0 AND `conf_catid` = $cat");
            $cat = false;
        }
        if (empty($cat)) {
            // Insert config category ( always XOOPS_CONF_AUTH = 7 )
            $db->queryF("INSERT INTO " . $db->prefix("configcategory") . " (confcat_id,confcat_name) VALUES (7,'_MD_AM_AUTHENTICATION')");
        }
        // Insert config values
        $table = $db->prefix('config');
        $data = array(
            'auth_method'              => "'_MD_AM_AUTHMETHOD', 'xoops', '_MD_AM_AUTHMETHODDESC', 'select', 'text', 1",
            'ldap_port'                => "'_MD_AM_LDAP_PORT', '389', '_MD_AM_LDAP_PORT', 'textbox', 'int', 2 ",
            'ldap_server'              => "'_MD_AM_LDAP_SERVER', 'your directory server', '_MD_AM_LDAP_SERVER_DESC', 'textbox', 'text', 3 ",
            'ldap_manager_dn'          => "'_MD_AM_LDAP_MANAGER_DN', 'manager_dn', '_MD_AM_LDAP_MANAGER_DN_DESC', 'textbox', 'text', 5",
            'ldap_manager_pass'        => "'_MD_AM_LDAP_MANAGER_PASS', 'manager_pass', '_MD_AM_LDAP_MANAGER_PASS_DESC', 'textbox', 'text', 6",
            'ldap_version'             => "'_MD_AM_LDAP_VERSION', '3', '_MD_AM_LDAP_VERSION_DESC', 'textbox', 'text', 7",
            'ldap_users_bypass'        => "'_MD_AM_LDAP_USERS_BYPASS', '" . serialize(array('admin')) . "', '_MD_AM_LDAP_USERS_BYPASS_DESC', 'textarea', 'array', 8",
            'ldap_loginname_asdn'      => "'_MD_AM_LDAP_LOGINNAME_ASDN', 'uid_asdn', '_MD_AM_LDAP_LOGINNAME_ASDN_D', 'yesno', 'int', 9",
            'ldap_loginldap_attr'      => "'_MD_AM_LDAP_LOGINLDAP_ATTR', 'uid', '_MD_AM_LDAP_LOGINLDAP_ATTR_D', 'textbox', 'text', 10",
            'ldap_filter_person'       => "'_MD_AM_LDAP_FILTER_PERSON', '', '_MD_AM_LDAP_FILTER_PERSON_DESC', 'textbox', 'text', 11",
            'ldap_domain_name'         => "'_MD_AM_LDAP_DOMAIN_NAME', 'mydomain', '_MD_AM_LDAP_DOMAIN_NAME_DESC', 'textbox', 'text', 12",
            'ldap_provisionning'       => "'_MD_AM_LDAP_PROVIS', '0', '_MD_AM_LDAP_PROVIS_DESC', 'yesno', 'int', 13",
            'ldap_provisionning_group' => "'_MD_AM_LDAP_PROVIS_GROUP', 'a:1:{i:0;s:1:\"2\";}', '_MD_AM_LDAP_PROVIS_GROUP_DSC', 'group_multi', 'array', 14",
            'ldap_mail_attr'           => "'_MD_AM_LDAP_MAIL_ATTR', 'mail', '_MD_AM_LDAP_MAIL_ATTR_DESC', 'textbox', 'text', 15",
            'ldap_givenname_attr'      => "'_MD_AM_LDAP_GIVENNAME_ATTR', 'givenname', '_MD_AM_LDAP_GIVENNAME_ATTR_DSC', 'textbox', 'text', 16",
            'ldap_surname_attr'        => "'_MD_AM_LDAP_SURNAME_ATTR', 'sn', '_MD_AM_LDAP_SURNAME_ATTR_DESC', 'textbox', 'text', 17",
        );
        foreach ($data as $name => $values) {
            if (!getDbValue($db, 'config', 'conf_id', "`conf_modid`=0 AND `conf_catid`=7 AND `conf_name`='$name'")) {
                $this->query("INSERT INTO `$table` (conf_modid,conf_catid,conf_name,conf_title,conf_value,conf_desc,conf_formtype,conf_valuetype,conf_order) " . "VALUES ( 0,7,'$name',$values)");
            }
        }
        // Insert auth_method config options
        $id = getDbValue($db, 'config', 'conf_id', "`conf_modid`=0 AND `conf_catid`=7 AND `conf_name`='auth_method'");
        $table = $db->prefix('configoption');
        $data = array(
            '_MD_AM_AUTH_CONFOPTION_XOOPS' => 'xoops', '_MD_AM_AUTH_CONFOPTION_LDAP' => 'ldap',
            '_MD_AM_AUTH_CONFOPTION_AD'    => 'ad',
        );
        $this->query("DELETE FROM `$table` WHERE `conf_id`=$id");
        foreach ($data as $name => $value) {
            $this->query("INSERT INTO `$table` (confop_name, confop_value, conf_id) VALUES ('$name', '$value', $id)");
        }
        return true;
    }
}

$upg = new upgrade_2014();
return $upg;
