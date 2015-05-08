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
 * @author          TDM Xoops (AKA Developers)
 * @version         $Id: tables.php 10665 2012-12-27 10:14:15Z timgno $
 */
//define("_MI_TDMCREATE_NAME", "TDMCreate");
//define("_MI_TDMCREATE_DESC", "Creation modules developed by TDM Xoops");
//Menu
//define("_MI_TDMCREATE_ADMENU1", "Dashboard");
//define("_MI_TDMCREATE_ADMENU2", "Modules");
//define("_MI_TDMCREATE_ADMENU3", "Extensions");
//define("_MI_TDMCREATE_ADMENU4", "Tables");
//define("_MI_TDMCREATE_ADMENU5", "Fields");
//define("_MI_TDMCREATE_ADMENU6", "Import");
//define("_MI_TDMCREATE_ADMENU7", "Building");
//define("_MI_TDMCREATE_ADMENU8", "Information");
// 1.37
//define("_MI_TDMCREATE_PREFERENCE_EDITOR", "Editor");
//define("_MI_TDMCREATE_PREFERENCE_EDITOR_DESC", "Select an editor to write");
//define("_MI_TDMCREATE_PREFERENCE_MAXSIZE","Maximum size of images");
//define("_MI_TDMCREATE_PREFERENCE_MAXSIZE_DESC","Set maximum size of images in Bytes");
//define('_MI_TDMCREATE_PREFERENCE_MIMETYPES', "Mime Types");
//define('_MI_TDMCREATE_PREFERENCE_MIMETYPES_DESC', "Mime Types for images");
//define("_MI_TDMCREATE_PREFERENCE_PAGER", "Admin per page");
//define("_MI_TDMCREATE_PREFERENCE_PAGER_DESC", "Set number of tables to view per page in admin.");

// 2.01
//define("_MI_TDMCREATE_PREFERENCE_NAME","Module Name");
//define("_MI_TDMCREATE_PREFERENCE_VERSION","Module Version");
//define("_MI_TDMCREATE_PREFERENCE_AUTHOR","Module Author");
//define("_MI_TDMCREATE_PREFERENCE_AUTHOREMAIL","Author's Email");
//define("_MI_TDMCREATE_PREFERENCE_AUTHORWEBSITEURL","Author's Website URL");
//define("_MI_TDMCREATE_PREFERENCE_AUTHORWEBSITENAME","Author's Website Name");
//define("_MI_TDMCREATE_PREFERENCE_LICENSE","License");
//define("_MI_TDMCREATE_PREFERENCE_LICENSEURL","License URL");
//define("_MI_TDMCREATE_PREFERENCE_CREDITS","Credits");
//define("_MI_TDMCREATE_PREFERENCE_RELEASEINFO","Modules Release Info");
//define("_MI_TDMCREATE_PREFERENCE_RELEASEFILE","Module Release File");
//define("_MI_TDMCREATE_PREFERENCE_MANUAL","Modules Manual");
//define("_MI_TDMCREATE_PREFERENCE_MANUALFILE","Manual file");
//define("_MI_TDMCREATE_PREFERENCE_IMAGE","Modules Image");
//define("_MI_TDMCREATE_PREFERENCE_DEMOSITEURL","Demo Website URL");
//define("_MI_TDMCREATE_PREFERENCE_DEMOSITENAME","Demo Website Name");
//define("_MI_TDMCREATE_PREFERENCE_SUPPORTURL","Support Website URL");
//define("_MI_TDMCREATE_PREFERENCE_SUPPORTNAME","Support Website");
//define("_MI_TDMCREATE_PREFERENCE_WEBSITEURL","Module website URL");
//define("_MI_TDMCREATE_PREFERENCE_WEBSITENAME","Module Website name");
//define("_MI_TDMCREATE_PREFERENCE_RELEASEDATE","Release Date");
//define("_MI_TDMCREATE_PREFERENCE_STATUS","Module status");
//define("_MI_TDMCREATE_PREFERENCE_DISPLAYADMINSIDE","Visible in Admin Panel");
//define("_MI_TDMCREATE_PREFERENCE_DISPLAYUSERSIDE","Visible in User side");
//define("_MI_TDMCREATE_PREFERENCE_ACTIVESEARCH","Allow Search");
//define("_MI_TDMCREATE_PREFERENCE_ACTIVECOMMENTS","Allow Comments");
//define("_MI_TDMCREATE_PREFERENCE_DESCRIPTION","Module Description");
//define("_MI_TDMCREATE_PREFERENCE_DISPLAYSUBMENU","Display Submenu");
//define("_MI_TDMCREATE_PREFERENCE_ACTIVENOTIFIES","Allow Notifies");
//define("_MI_TDMCREATE_PREFERENCE_PAYPALBUTTON","Paypal Button Donations");
//define("_MI_TDMCREATE_PREFERENCE_REVISION","Svn Revision");
////define("_MI_TDMCREATE_PREFERENCE_MODULEDESCRIPTION","Module Description");