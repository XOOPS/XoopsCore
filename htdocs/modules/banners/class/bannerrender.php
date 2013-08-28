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
 * banners module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class BannerRender
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Display banner
     *
     * @param $nb_banner
     * @param $align
     * @param $client
     * @param $ids
     *
     * @return string
     */
    public function displayBanner($nb_banner = 1, $align = 'H', $client = array(), $ids = '')
    {
        $xoops = Xoops::getInstance();
        XoopsLoad::addMap(array('banners' => dirname(__FILE__) . '/helper.php'));
        $helper = Banners::getInstance();
        if ($xoops->isActiveModule('banners')) {
            // Get banners handler
            $banner_Handler = $helper->getHandlerBanner();
            // Display banner
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('banner_status', 0, '!='));
            $criteria->setSort('RAND()');
            if (!empty($client)) {
                if (!in_array(0, $client)) {
                    $criteria->add(new Criteria('banner_cid', '(' . implode(',', $client) . ')', 'IN'));
                }
            }
            if ($ids == '') {
                $criteria->setLimit($nb_banner);
                $criteria->setStart(0);
            } else {
                $criteria->add(new Criteria('banner_bid', '(' . $ids . ')', 'IN'));
            }
            $banner_arr = $banner_Handler->getall($criteria);
            $numrows = count($banner_arr);
            $bannerobject = '';
            if ($numrows > 0) {
                foreach (array_keys($banner_arr) as $i) {
                    $imptotal = $banner_arr[$i]->getVar("banner_imptotal");
                    $impmade = $banner_arr[$i]->getVar("banner_impmade");
                    $htmlbanner = $banner_arr[$i]->getVar("banner_htmlbanner");
                    $htmlcode = $banner_arr[$i]->getVar("banner_htmlcode");
                    $imageurl = $banner_arr[$i]->getVar("banner_imageurl");
                    $bid = $banner_arr[$i]->getVar("banner_bid");
                    $clickurl = $banner_arr[$i]->getVar("banner_clickurl");
                    /**
                     * Print the banner
                     */
                    if ($htmlbanner) {
                        $bannerobject .= $htmlcode;
                    } else {
                        if (stristr($imageurl, '.swf')) {
                            $bannerobject .= '<a href="' . XOOPS_URL . '/modules/banners/index.php?op=click&amp;bid=' . $bid . '" rel="external" title="' . $clickurl . '"></a>' . '<object type="application/x-shockwave-flash" width="468" height="60" data="' . $imageurl . '" style="z-index:100;">' . '<param name="movie" value="' . $imageurl . '" />' . '<param name="wmode" value="opaque" />' . '</object>';
                        } else {
                            $bannerobject .= '<a href="' . XOOPS_URL . '/modules/banners/index.php?op=click&amp;bid=' . $bid . '" rel="external" title="' . $clickurl . '"><img src="' . $imageurl . '" alt="' . $clickurl . '" /></a>';
                        }
                    }
                    if ($align == 'V') {
                        $bannerobject .= '<br /><br />';
                    } else {
                        $bannerobject .= '&nbsp;';
                    }
                    if ($helper->getConfig('banners_myip') == $xoops->getEnv('REMOTE_ADDR')) {
                        // EMPTY
                    } else {
                        /**
                         * Check if this impression is the last one
                         */
                        $impmade = $impmade + 1;
                        if ($imptotal > 0 && $impmade >= $imptotal) {
                            $xoopsDB->queryF(sprintf('UPDATE %s SET banner_status = %u, banner_dateend = %u WHERE banner_bid = %u', $xoopsDB->prefix('banners_banner'), 0, time(), $bid));
                        } else {
                            $xoopsDB->queryF(sprintf('UPDATE %s SET banner_impmade = %u WHERE banner_bid = %u', $xoopsDB->prefix('banners_banner'), $impmade, $bid));
                        }
                    }
                }
                return $bannerobject;
            }
        }
    }
}