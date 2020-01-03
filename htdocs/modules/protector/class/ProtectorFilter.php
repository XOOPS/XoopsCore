<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Protector
 *
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

// Abstract of each filter classes
class ProtectorFilterAbstract
{
    public $protector = null;

    public $errors = [];

    public function ProtectorFilterAbstract()
    {
        $xoops = Xoops::getInstance();
        $language = $xoops->getConfig('language');
        $this->protector = Protector::getInstance();
        $lang = !$language ? @$this->protector->_conf['default_lang'] : $language;
        @include_once dirname(__DIR__) . '/language/' . $lang . '/main.php';
        if (!defined('_MD_PROTECTOR_YOUAREBADIP')) {
            include_once dirname(__DIR__) . '/language/english/main.php';
        }
    }

    public function isMobile()
    {
        if (class_exists('Wizin_User')) {
            // WizMobile (gusagi)
            $user = Wizin_User::getSingleton();

            return $user->bIsMobile;
        }
        if (defined('HYP_K_TAI_RENDER') && HYP_K_TAI_RENDER) {
            // hyp_common ktai-renderer (nao-pon)
            return true;
        }

        return false;
    }
}

// Filter Handler class (singleton)
class ProtectorFilterHandler
{
    public $protector = null;

    public $filters_base = '';

    public function ProtectorFilterHandler()
    {
        $this->protector = Protector::getInstance();
        $this->filters_base = dirname(__DIR__) . '/filters_enabled';
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    // return: false : execute default action
    public function execute($type)
    {
        $ret = 0;

        $dh = opendir($this->filters_base);
        while (false !== ($file = readdir($dh))) {
            if (0 === strncmp($file, $type . '_', mb_strlen($type) + 1)) {
                include_once $this->filters_base . '/' . $file;
                $plugin_name = 'protector_' . mb_substr($file, 0, -4);
                if (function_exists($plugin_name)) {
                    // old way
                    $ret |= call_user_func($plugin_name);
                } else {
                    if (class_exists($plugin_name)) {
                        // newer way
                        $plugin_obj = new $plugin_name(); //old code is -> $plugin_obj = new $plugin_name() ; //hack by Trabis
                        $ret |= $plugin_obj->execute();
                    }
                }
            }
        }
        closedir($dh);

        return $ret;
    }
}
