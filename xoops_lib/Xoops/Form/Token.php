<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Form;

/**
 * Token - a security token form element
 *
 * @category  Xoops\Form\Token
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Token extends Hidden
{
    /**
     * Constructor
     *
     * @param string|array $name    name attribute or array of all attributes
     * @param integer      $timeout timeout in seconds for generated token
     */
    public function __construct($name = 'XOOPS_TOKEN', $timeout = 0)
    {
        if (is_array($name)) {
            parent::__construct($name);
        } else {
            parent::__construct([]);
            $this->set('name', $name);
            $this->set(':timeout', $timeout);
        }
        $name = $this->get('name', 'XOOPS_TOKEN');
        if (substr($name, -8) !== '_REQUEST') {
            $this->set('name', $name.'_REQUEST');
        }
        $this->set('value', \Xoops::getInstance()->security()->createToken($this->get(':timeout', 0), $name));
    }
}
