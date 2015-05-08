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
//Menu
//define("_AM_TDMCREATE_ADMIN_INDEX", "Index");
//define("_AM_TDMCREATE_ADMIN_MODULES", "Add Module");
//define("_AM_TDMCREATE_ADMIN_TABLES", "Add Table");
//define("_AM_TDMCREATE_ADMIN_CONST", "Build Module");
//define("_AM_TDMCREATE_ADMIN_ABOUT", "Info");
//define("_AM_TDMCREATE_ADMIN_PREFERENCES", "Preferences");
//define("_AM_TDMCREATE_ADMIN_UPDATE", "Update");
//define("_AM_TDMCREATE_ADMIN_NUMMODULES", "Quantity Units");
//define("_AM_TDMCREATE_STATISTICS", "Statistics");
//define("_AM_TDMCREATE_THEREARE_NUMMODULES", "&#45; %s modules stored in the Database");
//define("_AM_TDMCREATE_THEREARE_NUMTABLES", "&#45; %s tables stored in the Database");


// Index Admin
//define("_AM_TDMCREATE_INDEX_NMTOTAL", "There are %s modules in the Database");
//define("_AM_TDMCREATE_INDEX_NETOTAL", "There are %s extensions in the Database");
//define("_AM_TDMCREATE_INDEX_NTTOTAL", "There are %s tables in the Database");
//define("_AM_TDMCREATE_INDEX_NITOTAL", "There are %s old modules imported in the Database");

//General
//define("_AM_TDMCREATE_FORMOK", "Successfully saved");
//define("_AM_TDMCREATE_FORMDELOK", "Successfully eliminated");
//define("_AM_TDMCREATE_FORMSUREDEL", "Are you sure you want to <b><span style='color : Red'>delete: %s </span></b>");
//define("_AM_TDMCREATE_FORMSURERENEW", "Are you sure you want to <b><span style='color : Red'>update: %s </span></b>");
//define("_AM_TDMCREATE_FORMUPLOAD", "Upload file");
//define("_AM_TDMCREATE_FORMIMAGE_PATH", "Files in %s ");
//define("_AM_TDMCREATE_FORMACTION", "Action");
//define("_AM_TDMCREATE_FORMEDIT", "Modification");
//define("_AM_TDMCREATE_FORMDEL", "Clear");
//define("_AM_TDMCREATE_FORMFIELDS", "Edit fields");
//define("_AM_TDMCREATE_FORM_INFO_TABLE", "Information on the table");
//define("_AM_TDMCREATE_FORM_INFO_TABLE_FIELD", "You can add your choice 3 fields in this table: '<b>table</b>'_submitter, '<b>table</b>'_created, '<b>table</b>'_online");

//define("_AM_TDMCREATE_NAME", "Name");
//define("_AM_TDMCREATE_BLOCKS", "Blocks");
//define("_AM_TDMCREATE_NB_FIELDS", "Number of fields");
//define("_AM_TDMCREATE_IMAGE", "Image");
//define("_AM_TDMCREATE_IMGURL", "Image Url");
//define("_AM_TDMCREATE_DISPLAY_ADMIN", "Visible Admin");
// 1.37
//define("_AM_TDMCREATE_DISPLAY_USER", "Visible User");

//Modules.php
//Form
//define("_AM_TDMCREATE_MODULE_ADD", "Add a new module");
//define("_AM_TDMCREATE_MODULE_EDIT", "Edit module");
////define("_AM_TDMCREATE_MODULE_IMPORTANT", "Required Information");

//define("_AM_TDMCREATE_MODULE_IMPORTANT", "Information");
//define("_AM_TDMCREATE_MODULE_NOTIMPORTANT", "Optional Information");
//define("_AM_TDMCREATE_MODULE_ID", "Id");
//define("_AM_TDMCREATE_MODULE_NAME", "Name");
//define("_AM_TDMCREATE_MODULE_VERSION", "Version");
//define("_AM_TDMCREATE_MODULE_DESCRIPTION", "Description");
//define("_AM_TDMCREATE_MODULE_AUTHOR", "Author");
//define("_AM_TDMCREATE_MODULE_AUTHOR_MAIL", "Author Email");
//define("_AM_TDMCREATE_MODULE_AUTHOR_WEBSITE_URL", "Author Site Url");
//define("_AM_TDMCREATE_MODULE_AUTHOR_WEBSITE_NAME", "Author Site Name");
//define("_AM_TDMCREATE_MODULE_CREDITS", "Credits");	
//define("_AM_TDMCREATE_MODULE_LICENSE", "License");
//define("_AM_TDMCREATE_MODULE_RELEASE_INFO", "Release Info");	
//define("_AM_TDMCREATE_MODULE_RELEASE_FILE", "Release File");
//define("_AM_TDMCREATE_MODULE_MANUAL", "Manual");	
//define("_AM_TDMCREATE_MODULE_MANUAL_FILE", "Manual File");
//define("_AM_TDMCREATE_MODULE_IMAGE", "Image");
//define("_AM_TDMCREATE_MODULE_DEMO_SITE_URL", "Demo Site Url");	
//define("_AM_TDMCREATE_MODULE_DEMO_SITE_NAME", "Demo Site Name");	
//define("_AM_TDMCREATE_MODULE_FORUM_SITE_URL", "Forum URL");
//define("_AM_TDMCREATE_MODULE_FORUM_SITE_NAME", "Forum Name");
//define("_AM_TDMCREATE_MODULE_WEBSITE_URL", "Module Website URL");
//define("_AM_TDMCREATE_MODULE_WEBSITE_NAME", "Module Website Name");
//define("_AM_TDMCREATE_MODULE_RELEASE", "Release");
//define("_AM_TDMCREATE_MODULE_STATUS", "Status");
//define("_AM_TDMCREATE_MODULE_PAYPALBTN", "Button for Donations");
//define("_AM_TDMCREATE_MODULE_SVN", "Subversion module");
//define("_AM_TDMCREATE_MODULE_ADMIN", "Visible Admin");
//define("_AM_TDMCREATE_MODULE_USER", "Visible User");
//define("_AM_TDMCREATE_MODULE_SEARCH", "Enable Search");
//define("_AM_TDMCREATE_MODULE_COMMENTS", "Enable Comments");
//define("_AM_TDMCREATE_MODULE_NOTIFICATIONS", "Enable Notifications");
// Added in version 1.39
//define("_AM_TDMCREATE_MODULE_NBFIELDS", "Fields Number");
//define("_AM_TDMCREATE_MODULE_BLOCKS", "Blocks");
//define("_AM_TDMCREATE_MODULE_ADMIN_LIST", "Admin");
//define("_AM_TDMCREATE_MODULE_USER_LIST", "User");
//define("_AM_TDMCREATE_MODULE_SUBMENU_LIST", "Submenu");
//define("_AM_TDMCREATE_MODULE_SEARCH_LIST", "Search");
//define("_AM_TDMCREATE_MODULE_COMMENTS_LIST", "Comments");
//define("_AM_TDMCREATE_MODULE_NOTIFICATIONS_LIST", "Notifications");
//define("_AM_TDMCREATE_MODULE_ERROR_NOMODULES", "There are no modules");

//define("_AM_TDMCREATE_EXTENSION_ADD", "Add a new extension");
//define("_AM_TDMCREATE_EXTENSION_EDIT", "Edit extension");

//define("_AM_TDMCREATE_EXTENSION_IMPORTANT", "Information");
//define("_AM_TDMCREATE_EXTENSION_NOTIMPORTANT", "Optional Information");
//define("_AM_TDMCREATE_EXTENSION_ID", "Id");
//define("_AM_TDMCREATE_EXTENSION_NAME", "Name");
//define("_AM_TDMCREATE_EXTENSION_VERSION", "Version");
//define("_AM_TDMCREATE_EXTENSION_DESCRIPTION", "Description");
//define("_AM_TDMCREATE_EXTENSION_AUTHOR", "Author");
//define("_AM_TDMCREATE_EXTENSION_AUTHOR_MAIL", "Author Email");
//define("_AM_TDMCREATE_EXTENSION_AUTHOR_WEBSITE_URL", "Author Site Url");
//define("_AM_TDMCREATE_EXTENSION_AUTHOR_WEBSITE_NAME", "Author Site Name");
//define("_AM_TDMCREATE_EXTENSION_CREDITS", "Credits");	
//define("_AM_TDMCREATE_EXTENSION_LICENSE", "License");
//define("_AM_TDMCREATE_EXTENSION_RELEASE_INFO", "Release Info");	
//define("_AM_TDMCREATE_EXTENSION_RELEASE_FILE", "Release File");
//define("_AM_TDMCREATE_EXTENSION_MANUAL", "Manual");	
//define("_AM_TDMCREATE_EXTENSION_MANUAL_FILE", "Manual File");
//define("_AM_TDMCREATE_EXTENSION_IMAGE", "Image");
//define("_AM_TDMCREATE_EXTENSION_DEMO_SITE_URL", "Demo Site Url");	
//define("_AM_TDMCREATE_EXTENSION_DEMO_SITE_NAME", "Demo Site Name");	
//define("_AM_TDMCREATE_EXTENSION_FORUM_SITE_URL", "Forum URL");
//define("_AM_TDMCREATE_EXTENSION_FORUM_SITE_NAME", "Forum Name");
//define("_AM_TDMCREATE_EXTENSION_WEBSITE_URL", "Module Website URL");
//define("_AM_TDMCREATE_EXTENSION_WEBSITE_NAME", "Module Website Name");
//define("_AM_TDMCREATE_EXTENSION_RELEASE", "Release");
//define("_AM_TDMCREATE_EXTENSION_STATUS", "Status");
//define("_AM_TDMCREATE_EXTENSION_PAYPALBTN", "Button for Donations");
//define("_AM_TDMCREATE_EXTENSION_SVN", "Subversion module");
//define("_AM_TDMCREATE_EXTENSION_ADMIN", "Visible Admin");
//define("_AM_TDMCREATE_EXTENSION_USER", "Visible User");
//define("_AM_TDMCREATE_EXTENSION_SUBMENU", "Visible Submenu");
//define("_AM_TDMCREATE_EXTENSION_SEARCH", "Enable Search");
//define("_AM_TDMCREATE_EXTENSION_COMMENTS", "Enable Comments");
//define("_AM_TDMCREATE_EXTENSION_NOTIFICATIONS", "Enable Notifications");
// Added in version 2.01
//define("_AM_TDMCREATE_EXTENSION_NBFIELDS", "Fields Number");
//define("_AM_TDMCREATE_EXTENSION_BLOCKS", "Blocks");
//define("_AM_TDMCREATE_EXTENSION_ADMIN_LIST", "Admin");
//define("_AM_TDMCREATE_EXTENSION_USER_LIST", "User");
//define("_AM_TDMCREATE_EXTENSION_SUBMENU_LIST", "Submenu");
//define("_AM_TDMCREATE_EXTENSION_SEARCH_LIST", "Search");
//define("_AM_TDMCREATE_EXTENSION_COMMENTS_LIST", "Comments");
//define("_AM_TDMCREATE_EXTENSION_NOTIFICATIONS_LIST", "Notifications");
//define("_AM_TDMCREATE_EXTENSIONS_LIST", "Extension List");
//define("_AM_TDMCREATE_EXTENSION_ERROR_NOEXTENSIONS", "There are no extensions");

//Tables.php
//Form1
//define("_AM_TDMCREATE_TABLE_ADD", "Add a new table");
//define("_AM_TDMCREATE_TABLE_EDIT", "Edit Table");
//define("_AM_TDMCREATE_TABLE_MODULES", "Choose a module");
//define("_AM_TDMCREATE_TABLE_NAME", "Table Name");
//define("_AM_TDMCREATE_TABLE_NAME_DESC", "Unique Name for this Table");
//define("_AM_TDMCREATE_TABLE_NBFIELDS", "Number fields");
//define("_AM_TDMCREATE_TABLE_NBFIELDS_DESC", "Number of fields for this table");
//define("_AM_TDMCREATE_TABLE_FIELDNAME", "Field Name");
//define("_AM_TDMCREATE_TABLE_FIELDNAME_DESC", "This is the prefix of field name (optional)<br />If you leave the field blank,<br />doesn't appear anything in the fields of the next screen,<br />otherwise you'll see all the fields with a prefix type (e.g: <span class='bold'>fieldname_</span>)");
//define("_AM_TDMCREATE_TABLE_IMAGE", "Table Logo");
//define("_AM_TDMCREATE_TABLE_BLOCKS", "Create blocks for this table");
//define("_AM_TDMCREATE_TABLE_BLOCKS_DESC", "(blocs: random, latest, today)");
//define("_AM_TDMCREATE_TABLE_DISPLAY_ADMIN", "Use the side view of Admin");
//define("_AM_TDMCREATE_TABLE_DISPLAY_USER", "Use the side view of User");
//define("_AM_TDMCREATE_TABLE_SUBMENU", "Use view TAB Submenu");
//define("_AM_TDMCREATE_TABLE_SEARCH", "Active search for this table");
//define("_AM_TDMCREATE_TABLE_EXIST", "The name specified for this table is already in use");
//define("_AM_TDMCREATE_TABLE_COMMENTS", "Enable Comments for this table");

//define("_AM_TDMCREATE_TABLE_ID", "Id");
//define("_AM_TDMCREATE_TABLE_ADMIN_LIST", "Display Admin");
//define("_AM_TDMCREATE_TABLE_USER_LIST", "Display User");
//define("_AM_TDMCREATE_TABLE_SUBMENU_LIST", "Display Submenu");
//define("_AM_TDMCREATE_TABLE_SEARCH_LIST", "Active Search");
//define("_AM_TDMCREATE_TABLE_COMMENTS_LIST", "Active Comments");
//define("_AM_TDMCREATE_TABLE_NOTIFICATIONS_LIST", "Active Notifies");
// v1.38
//define("_AM_TDMCREATE_TABLE_IMAGE_DESC", "<span class='red bold'>Attention</span>: If you want to choose a new image, is best to name it with the module name before and follow with the name of the image so as not to overwrite any images with the same name, in the <span class='bold'>Frameworks/moduleclasses/moduleadmin/icons/32/</span>. Otherwise an other solution, would be to insert the images in the module, a new folder is created, with the creation of the same module - <span class='bold'>images/32</span>.");
// Added in version 1.39
//define("_AM_TDMCREATE_TABLE_NOTIFY", "Enable Notifications");
//define("_AM_TDMCREATE_TABLE_ERROR_NOTABLES", "There are no tables");
//define("_AM_TDMCREATE_TABLE_ERROR_NOMODULES", "There are no modules");
//Form2
//define("_AM_TDMCREATE_FIELD_ADD", "Add fields");
//define("_AM_TDMCREATE_FIELD_EDIT", "Edit fields");
//define("_AM_TDMCREATE_FIELD_NUMBER", "N&#176;");
//define("_AM_TDMCREATE_FIELD_NAME", "Field Name");
//define("_AM_TDMCREATE_FIELD_TYPE", "Type");
//define("_AM_TDMCREATE_FIELD_VALUE", "Value");
//define("_AM_TDMCREATE_FIELD_ATTRIBUTE", "Attribute");
//define("_AM_TDMCREATE_FIELD_NULL", "Null");
//define("_AM_TDMCREATE_FIELD_DEFAULT", "Default");
//define("_AM_TDMCREATE_FIELD_KEY", "Key");
//define("_AM_TDMCREATE_FIELD_AUTO_INCREMENT", " Auto Increment");
// Others
//define("_AM_TDMCREATE_FIELD_OTHERS", "Others");
//define("_AM_TDMCREATE_FIELD_ELEMENTS", "Options Elements");
//define("_AM_TDMCREATE_FIELD_ELEMENT_NAME", "Form: Element");
//define("_AM_TDMCREATE_FIELD_DISPLAY_ADMIN", "Page: Show Admin Side");
//define("_AM_TDMCREATE_FIELD_DISPLAY_USER", "Page: Show User Side");
//define("_AM_TDMCREATE_FIELD_DISPLAY_BLOCK", "Block: View");
//define("_AM_TDMCREATE_FIELD_MAINFIELD", "Table: Main Field");
//define("_AM_TDMCREATE_FIELD_SEARCH", "Search: Index");
//define("_AM_TDMCREATE_FIELD_REQUIRED", "Field: Required");
//define("_AM_TDMCREATE_FIELD_ERROR_NOFIELDS", "There are no fields");
//define("_AM_TDMCREATE_ADMIN_SUBMIT", "Send");

//Const.php
//define("_AM_TDMCREATE_CONST_MODULES", "Select the module you want to build");
//define("_AM_TDMCREATE_CONST_TABLES", "Select the table you want to build");
//Creation
//OK
//define("_AM_TDMCREATE_CONST_OK_ARCHITECTURE", "Created structure of module (index.html, icons , languages, admin, ...)");
//define("_AM_TDMCREATE_CONST_OK_COMS", "Created files for comments");
//define("_AM_TDMCREATE_CONST_OK_DOCS", "Created <b>%s</b> file in the docs");
//define("_AM_TDMCREATE_CONST_OK_CSS", "Created <b>%s</b> file in the css folder");
//define("_AM_TDMCREATE_CONST_OK_ROOTS", "Created <b>%s</b> file in the root of the form");
//define("_AM_TDMCREATE_CONST_OK_CLASSES", "Creating the <b>files %s </b> in the class folder");
//define("_AM_TDMCREATE_CONST_OK_BLOCKS", "Created the file in the <b>%s</b> blocks folder");
//define("_AM_TDMCREATE_CONST_OK_SQL", "Created <b>%s</b> file in your sql");
//define("_AM_TDMCREATE_CONST_OK_ADMINS", "Created <b>%s</b> file in the admin folder");
//define("_AM_TDMCREATE_CONST_OK_LANGUAGES", "Created <b>%s</b> file in the folder languages");
//define("_AM_TDMCREATE_CONST_OK_INCLUDES", "Created <b>%s</b> file in the folder includes");
//define("_AM_TDMCREATE_CONST_OK_TEMPLATES", "Created <b>%s</b> file in the templates");
//define("_AM_TDMCREATE_CONST_OK_TEMPLATES_BLOCS", "Created <b>%s</b> file in the templates/blocks");
//define("_AM_TDMCREATE_CONST_OK_TEMPLATES_ADMIN", "Created <b>%s</b> file in the templates/admin");

//NOTOK
//define("_AM_TDMCREATE_CONST_NOTOK_ARCHITECTURE", "Problems: Creating the module (index.html, icons ,...)");
//define("_AM_TDMCREATE_CONST_NOTOK_COMS", "Problems: Creating files for comments");
//define("_AM_TDMCREATE_CONST_NOTOK_DOCS", "Problems: Creating <b>%s</b> file in the docs folder");
//define("_AM_TDMCREATE_CONST_NOTOK_CSS", "Problems: Creating <b>%s</b> file in the folder css");
//define("_AM_TDMCREATE_CONST_NOTOK_ROOTS", "Problems: Creating <b>%s</b> file in the root of the form");
//define("_AM_TDMCREATE_CONST_NOTOK_CLASSES", "Problems: Creating <b>%s</b> file in your class folder");
//define("_AM_TDMCREATE_CONST_NOTOK_BLOCKS", "Problems: Creating <b>%s</b> file in blocks folder");
//define("_AM_TDMCREATE_CONST_NOTOK_SQL", "Problems: Creating <b>%s</b> file in sql folder");
//define("_AM_TDMCREATE_CONST_NOTOK_ADMINS", "Problems: Creating <b>%s</b> file in the admin folder");
//define("_AM_TDMCREATE_CONST_NOTOK_LANGUAGES", "Problems: Creating <b>%s</b> file in the language folder");
//define("_AM_TDMCREATE_CONST_NOTOK_INCLUDES", "Problems: Creating <b>%s</b> file in the include folder");
//define("_AM_TDMCREATE_CONST_NOTOK_TEMPLATES", "Problems: Creating <b>%s</b> file in the templates folder");
//define("_AM_TDMCREATE_CONST_NOTOK_TEMPLATES_BLOCS", "Problems: Creating <b>%s</b> file in the templates/blocks folder");
//define("_AM_TDMCREATE_CONST_NOTOK_TEMPLATES_ADMIN", "Problems: Creating <b>%s</b> file in the templates/admin folder");

//------------ new additions: Ver. 1.11 -----------------------

//define("_AM_TDMCREATE_ADMIN_PERMISSIONS", "Permissions");
//define("_AM_TDMCREATE_FORMON", "Online");
//define("_AM_TDMCREATE_FORMOFF", "Offline");

//define("_AM_TDMCREATE_TRANSLATION_PERMISSIONS_ACCESS", "Allowed to see");
//define("_AM_TDMCREATE_TRANSLATION_PERMISSIONS_SUBMIT", "Permission to post");

//blocks
//define("_AM_TDMCREATE_BLOCK_DAY", "Today");
//define("_AM_TDMCREATE_BLOCK_RANDOM", "Random");
//define("_AM_TDMCREATE_BLOCK_RECENT", "Recent");

//define("_AM_TDMCREATE_THEREARE_DATABASE1", "There <span style='color: #ff0000; font-weight: bold'>are %s </span>");
//define("_AM_TDMCREATE_THEREARE_DATABASE2", "in the database");
//define("_AM_TDMCREATE_THEREARE_PENDING", "There <span style='color: #ff0000; font-weight: bold'>are %s </span>");
//define("_AM_TDMCREATE_THEREARE_PENDING2", "waiting");

//define("_AM_TDMCREATE_FORMADD", "Add");

//define("_AM_TDMCREATE_MIMETYPES", "Mime types authorized for:");
//define("_AM_TDMCREATE_MIMESIZE", "Allowable size:");
//define("_AM_TDMCREATE_EDITOR", "Editor:");

//------------ new additions: Ver. 1.15 -----------------------
//define("_AM_TDMCREATE_ABOUT_WEBSITE_FORUM", "Forum Web Site");

//------------ new additions: Ver. 1.37 -----------------------
//define("_AM_TDMCREATE_MODULES_LIST", "Modules List");
//define("_AM_TDMCREATE_MODULE", "Module");
//define("_AM_TDMCREATE_EXTENSION", "Extension");
//define("_AM_TDMCREATE_TABLES_LIST", "Tables List");
//define("_AM_TDMCREATE_TABLE", "Table");

//------------ new additions: Ver. 1.38 -----------------------
//define("_AM_TDMCREATE_NOTMODULES", "<span class='red bold'>No module created, must create at least one before</span>");
////define("_AM_TDMCREATE_MAINTAINEDBY", " is mantained by ");
//define("_AM_TDMCREATE_MODULEADMIN_MISSING", "Module Admin Missing, Pleace! Install this Framework");
//define("_AM_TDMCREATE_MODULE_DISPLAY_SUBMENU", "Visible Submenu");
//define("_AM_TDMCREATE_MODULE_ACTIVE_NOTIFY", "Enable Notifications");
//define("_AM_TDMCREATE_NOTINSERTED", "<span class='red bold'>The module is not saved,<br />it is likely that you have used a name that already exists,<br />please change name for a new module.</span>");

//define("_AM_TDMCREATE_DELETE", "Delete");
//define("_AM_TDMCREATE_UPLOADS", "Uploads");
//define("_AM_TDMCREATE_IMAGE_PATH", "Image Path: %s");
//define("_AM_TDMCREATE_SUBMENU", "Submenu");  
//define("_AM_TDMCREATE_SEARCH", "Search");  
//define("_AM_TDMCREATE_COMMENTS", "Comments");  
//define("_AM_TDMCREATE_NOTIFIES", "Notifies");

//Error NoFrameworks
//define('_AM_MODULEADMIN_MISSING', "Error: You don&#39;t use the Frameworks \"admin module\". Please install this Frameworks");
//define('_AM_TDMCREATE_MAINTAINEDBY', "<span class='bold green'>%s</span><span class='small italic'> is maintained by the </span><a href='%s' title='Visit %s' class='tooltip' rel='external'>%s</a><span class='small italic'> and by </span><a href='http://www.xoops.org/modules/newbb/' title='Visit Xoops Community' class='tooltip' rel='external'>Xoops Community</a>");

//define("_AM_TDMCREATE_IMPORT", "Import old module");
//define("_AM_TDMCREATE_MODULE_IMPORT_TITLE", "Form Import old module");
//define("_AM_TDMCREATE_IMPORTS_LIST", "List of old modules Imported");

//define("_AM_TDMCREATE_IMPORT_ID", "Id");
//define("_AM_TDMCREATE_IMPORT_MID", "Module");
//define("_AM_TDMCREATE_IMPORT_NAME", "Name");
//define("_AM_TDMCREATE_IMPORT_NBTABLES", "Number Tables");
//define("_AM_TDMCREATE_IMPORT_TABLENAME", "Table Name");
//define("_AM_TDMCREATE_IMPORT_NBFIELDS", "Number Fields");
//define("_AM_TDMCREATE_IMPORT_FIELDNAME", "Field Name");
//define("_AM_TDMCREATE_IMPORT_ERROR_NOIMPORTS", "There are not old modules imported");

//define("_AM_TDMCREATE_BUILDING_TITLE", "Building");
//define("_AM_TDMCREATE_BUILDING_MODULES", "Building modules");
//define("_AM_TDMCREATE_BUILDING_EXTENSIONS", "Building Extensions");
//define("_AM_TDMCREATE_SELDEFMOD", "Select Module");
//define("_AM_TDMCREATE_SELDEFEXT", "Select Extension");
//define("_AM_TDMCREATE_BUILD", "Build Executed");
//define("_AM_TDMCREATE_BUILDING_SUCCESS", "Success");
//define("_AM_TDMCREATE_BUILDING_ERROR", "Error");
//define("_AM_TDMCREATE_BUILDING_FORM", "Building Form");