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
 * page module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         page
 * @since           2.6.0
 * @author          DuGris (aka Laurent JEN)
 * @version         $Id$
 */

class PageLocaleEn_US /*extends XoopsLocaleEn_US*/
{
    // Module
    const MODULE_NAME = "Page";
    const MODULE_DESC = "Module for creating pages";

    // Admin menu
    const SYSTEM_CONTENT = "Content";
    const SYSTEM_RELATED = "Related content";
    const SYSTEM_PERMISSIONS = "Permissions";
    const SYSTEM_ABOUT = "About";

    // Configurations
    const CONF_EDITOR = "Editor";
    const CONF_ADMINPAGER = "Number contents to display per page in admin page";
    const CONF_USERPAGER = "Number contents to display per page in user page";
    const CONF_DATEFORMAT = "Date format";
    const CONF_TIMEFORMAT = "time format";

    // Blocks
    const BLOCKS_CONTENTS = "Contents";
    const BLOCKS_CONTENTS_DSC = "Display contents";
    const BLOCKS_ID = "ID contents";
    const BLOCKS_ID_DSC = "Display contents by ID";

    // Notifications
    const NOTIFICATION_GLOBAL = "Global Contents";
    const NOTIFICATION_GLOBAL_DSC = "Notification options that apply to all contents.";
    const NOTIFICATION_ITEM = "Content";
    const NOTIFICATION_ITEM_DSC = "Notification options that apply to the current article.";
    const NOTIFICATION_GLOBAL_NEWCONTENT = "New content published";
    const NOTIFICATION_GLOBAL_NEWCONTENT_CAP = "Notify me when any new content is published.";
    const NOTIFICATION_GLOBAL_NEWCONTENT_DSC = "Receive notification when any new content is published.";
    const NOTIFICATION_GLOBAL_NEWCONTENT_SBJ = "[{X_SITENAME}] {X_MODULE} auto-notify : New content published";

    // Admin index.php
    const TOTALCONTENT = "There are <span class=\"red bold\">%s</span> contents in our database";
    const TOTALDISPLAY = "There are <span class=\"green bold\">%s</span> visible contents";
    const TOTALNOTDISPLAY = "There are <span class=\"red bold\">%s</span> contents not visible";

    // Admin content
    const A_ADD_CONTENT = "Add a new content";
    const A_EDIT_CONTENT = "Edit a content";
    const A_LIST_CONTENT = "List of contents";
    const E_NO_CONTENT = "There are no contents";
    const CONTENT_TIPS = "<ul><li>Add, update, copy or delete content</li></ul>";
    const CONTENT_TEXT_DESC = "Main content of the page";
    const CONTENT_META_KEYWORDS = "Metas keyword";
    const CONTENT_META_KEYWORDS_DSC = "Metas keyword separated by a comma";
    const CONTENT_META_DESCRIPTION = "Metas description";
    const CONTENT_OPTIONS_DSC = "Choose which information will be displayed";
    const CONTENT_SELECT_GROUPS = "Select groups that can view this content";
    const CONTENT_COPY = "[copy]";
    const E_WEIGHT = "You need a positive integer";
    const Q_ON_MAIN_PAGE = "Content displayed on the home page";
    const L_CONTENT_DOPDF = "PDF icon";
    const L_CONTENT_DOPRINT = "Print icon";
    const L_CONTENT_DOSOCIAL = "Social networks";
    const L_CONTENT_DOAUTHOR = "Author";
    const L_CONTENT_DOMAIL = "Mail icon";
    const L_CONTENT_DODATE = "Date";
    const L_CONTENT_DOHITS = "Hits";
    const L_CONTENT_DORATING = "Rating and vote count";
    const L_CONTENT_DOCOMS = "Comments";
    const L_CONTENT_DONCOMS = "Comments count";
    const L_CONTENT_DOTITLE = "Title";
    const L_CONTENT_DONOTIFICATIONS = "Notifications";

    // Admin related
    const A_ADD_RELATED = "Add a new related content";
    const A_EDIT_RELATED = "Edit a related content";
    const RELATED_TIPS = "<ul><li>This section allows you to create links between pages together</li></ul>";
    const RELATED_NAME = "Group name";
    const RELATED_NAVIGATION = "Navigation type";
    const L_RELATED_NAVIGATION_OPTION1 = "Arrow";
    const L_RELATED_NAVIGATION_OPTION2 = "Arrow with menu";
    const L_RELATED_NAVIGATION_OPTION3 = "Arrow with title content";
    const L_RELATED_NAVIGATION_OPTION4 = "Menu";
    const L_RELATED_NAVIGATION_OPTION5 = "Title content";
    const RELATED_MENU = "Menu";
    const RELATED_MENU_DSC = "The menu is a list with the names of related content";
    const E_NO_RELATED = "There are no related content";
    const E_NO_FREE_CONTENT = "There are no free content";
    const RELATED_MAIN = "Main content";

    // Admin permissions
    const PERMISSIONS_RATE = "Rate permissions";
    const PERMISSIONS_VIEW = "View permissions";

    // Admin Tabs form
    const TAB_MAIN = "Main";
    const TAB_METAS = "Metas";
    const TAB_OPTIONS = "Options";
    const TAB_PERMISSIONS = "Permissions";

    // main
    const YOUR_VOTE = "Your vote";

    // viewpage
    const E_NOT_EXIST = "This page does not exist in our database";

    // Print
    const PRINT_COMES = "This article comes from";
    const PRINT_URL = "The URL for this page is: ";

    // Block configuration
    const CONF_BLOCK_MODE = "Mode";
    const CONF_BLOCK_L_CONTENT = "Content";
    const CONF_BLOCK_L_LIST = "List";

    const CONF_BLOCK_ORDER = "Order by";
    const CONF_BLOCK_L_RECENT = "Recent";
    const CONF_BLOCK_L_HITS = "Hits";
    const CONF_BLOCK_L_RATING = "Rating";
    const CONF_BLOCK_L_RANDOM = "Random";

    const CONF_BLOCK_SORT = "Sort";
    const CONF_BLOCK_L_ASC = "Ascending";
    const CONF_BLOCK_L_DESC = "Descending";

    const CONF_BLOCK_CONTENTDISPLAY = "Content to display";
    const CONF_BLOCK_DISPLAY_NUMBER = "Number to display";
    const CONF_BLOCK_ALL_CONTENT = "Display all the content";
}
