<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Controller;

/**
 * XOOPS FrontController
 *
 * See the enclosed file license.txt for licensing information. If you did not
 * receive this file, get it at GNU http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @author          Alain091
 */

class FrontController extends AbstractController
{
	
	public function doHeader()
	{
	    $this->xoops->header($this->template);
	}

	public function doFooter()
	{
	    $this->xoops->footer();
	}
}
