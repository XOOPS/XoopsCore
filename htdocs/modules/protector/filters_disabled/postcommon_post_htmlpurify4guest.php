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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class protector_postcommon_post_htmlpurify4guest extends ProtectorFilterAbstract
{
    var $purifier;

    var $method;

    function execute()
    {
        $xoops = Xoops::getInstance();

        if ($xoops->isUser()) {
            return true;
        }

        // use HTMLPurifier inside Protector
        //require_once $xoops->path('lib/HTMLPurifier/HTMLPurifier.auto.php');
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache', 'SerializerPath', XOOPS_PATH);
        $config->set('Core', 'Encoding', XoopsLocale::getCharset());
        //$config->set('HTML', 'Doctype', 'HTML 4.01 Transitional');
        $this->purifier = new HTMLPurifier($config);
        $this->method = 'purify';

        $_POST = $this->purify_recursive($_POST);
        return true;
    }

    function purify_recursive($data)
    {
        if (is_array($data)) {
            return array_map(array($this, 'purify_recursive'), $data);
        } else {
            return strlen($data) > 32 ? call_user_func(array($this->purifier, $this->method), $data) : $data;
        }
    }
}