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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.6.0
 * @author          Timgno <txmodxoops@gmail.com>
 * @version         $Id: locale.php 10665 2012-12-27 10:14:15Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die("Restricted access");

class TDMCreateCLocale extends TDMCreateFile
{
    /**
     * Constructor
     *
     * @param TDMCreateFile|null $file
     * @param string $module
     */
	public function __construct(TDMCreateFile $file = null, $module = '', $text = '')
    {
        if (isset($file)) {
            $this->create($file, $module);
			$this->text = $text;
        }
    }
}