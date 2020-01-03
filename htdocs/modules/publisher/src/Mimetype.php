<?php

namespace XoopsModules\Publisher;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Kernel\XoopsObject;

/**
 *  Publisher class
 *
 * @package   Publisher
 * @since     1.0
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Nazar Aziz <nazar@panthersoftware.com>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */

/**
 * PublisherMimetype class
 */
class Mimetype extends XoopsObject
{
    public function __construct()
    {
        $this->initVar('mime_id', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('mime_ext', \XOBJ_DTYPE_TXTBOX, null, true, 60);
        $this->initVar('mime_types', \XOBJ_DTYPE_TXTAREA, null, false, 1024);
        $this->initVar('mime_name', \XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('mime_admin', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('mime_user', \XOBJ_DTYPE_INT, null, false);
    }
}
