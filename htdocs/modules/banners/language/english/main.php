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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */
//index.php
//define("_MD_BANNERS_INDEX_CLIENTNAME", "Displaying active banners for client: %s");
define("_MD_BANNERS_INDEX_DBERROR", "Database was not updated due to some error!");
define("_MD_BANNERS_INDEX_EMAIL", "Send E-mail stats");
define("_MD_BANNERS_INDEX_NO_ID","No valid ID detected");
define("_MD_BANNERS_INDEX_NO_REFERER", "No referer detected");
define("_MD_BANNERS_INDEX_ID", "ID");
define("_MD_BANNERS_INDEX_MAIL_MESSAGE", "Available Banner Statistics for the selected Banner at %s :\n\n\n
Client Name: %s\nBanner ID: %s\n
Banner Image: %s\n
Banner URL: %s\n\n
Impressions Purchased: %s\n
Impressions Made: %s\n
Impressions Left: %s\n
Clicks Received: %s\n
Clicks Percent: %f \n\n\n
Report Generated on: %s");
define("_MD_BANNERS_INDEX_MAIL_OK", "Available Banner statistics for the selected banner have been sent to your account email address.");
define("_MD_BANNERS_INDEX_MAIL_SUBJECT", "Your Banner Statistics at %s");
define("_MD_BANNERS_INDEX_NOMAIL", "Failed to send: E-Mail address does not exist.");
define("_MD_BANNERS_INDEX_NOBANNER", "You have no banners");
include_once('admin.php');
