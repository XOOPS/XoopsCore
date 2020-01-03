<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Html\Menu\Render\BreadCrumb;

/**
 * Extended User Profile
 *
 * @copyright       2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 */
$xoops = Xoops::getInstance();
$profileBreadcrumbs = $xoops->registry()->get('profile_breadcrumbs');

if (count($profileBreadcrumbs) > 1) {
    $breadCrumb = new BreadCrumb();
    $xoops->tpl()->assign('profile_breadcrumbs', $breadCrumb->render($profileBreadcrumbs));
}
$xoops->theme()->addStylesheet($xoops->url('modules/profile/assets/css/style.css'));
$xoops->footer();
