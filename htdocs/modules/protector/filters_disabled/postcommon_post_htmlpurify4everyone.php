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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
class protector_postcommon_post_htmlpurify4everyone extends ProtectorFilterAbstract
{
    public $purifier;

    public $method;

    public function execute()
    {
        $xoops = Xoops::getInstance();
        // use HTMLPurifier inside Protector
        //require_once $xoops->path('lib/HTMLPurifier/HTMLPurifier.auto.php');
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache', 'SerializerPath', \XoopsBaseConfig::get('lib-path'));
        $config->set('Core', 'Encoding', XoopsLocale::getCharset());
        //$config->set('HTML', 'Doctype', 'HTML 4.01 Transitional');
        $this->purifier = new HTMLPurifier($config);
        $this->method = 'purify';
        $_POST = $this->purify_recursive($_POST);
    }

    public function purify_recursive($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'purify_recursive'], $data);
        }

        return mb_strlen($data) > 32 ? call_user_func([$this->purifier, $this->method], $data) : $data;
    }
}
