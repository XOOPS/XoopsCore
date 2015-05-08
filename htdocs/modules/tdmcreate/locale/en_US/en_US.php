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
 * @author          DuGris (aka Laurent JEN)
 * @version         $Id: en_US.php 10956 2013-01-31 18:09:50Z timgno $
 */

defined("XOOPS_ROOT_PATH") or die("Restricted access");

class TDMCreateLocaleEn_US extends XoopsLocaleEn_US
{    
	const ADD_EXTENSION = "Add Extension";
	const ADD_FIELDS = "Add Fields";
	const ADD_MODULE = "Add Module";
	const ADD_TABLE = "Add Table";
	const ADD_LOCALE = "Add Module";
	const EDIT_EXTENSION = "Edit Extension";
	const EDIT_FIELDS = "Edit Fields";
	const EDIT_MODULE = "Edit Module";
	const EDIT_TABLE = "Edit Table";
	
    const ADMIN_MENU1 = "Dashboard";
	const ADMIN_MENU2 = "Modules";
	const ADMIN_MENU3 = "Tables";
	const ADMIN_MENU4 = "Fields";
	const ADMIN_MENU5 = "Locale";
	const ADMIN_MENU6 = "Import";
	const ADMIN_MENU7 = "Building";
	const ADMIN_MENU8 = "Information";
	
	const BUILD_EXTENSION  = "Build Extension";
	const BUILD_MODULE  = "Build Module";
	
	const DASHBOARD = "Dashboard";

	const C_ADMIN = "Visible Admin:";
	const C_AUTHOR_MAIL = "Author Email:";
	const C_AUTHOR_WEBSITE_URL = "Author Site Url:";
	const C_AUTHOR_WEBSITE_NAME = "Author Site Name:";
	const C_BLOCKS = "Enable Blocks:";
	const C_CHECK_ALL = "Check All:";
	const C_COMMENTS = "Enable Comments:";
	const C_CREDITS = "Credits:";
	const C_DEMO_SITE_URL = "Demo Site Url:";
	const C_DEMO_SITE_NAME = "Demo Site Name:";
	const C_DONATIONS = "Button Donations:";
	const C_IMAGE = "Image:";
	const C_LICENSE = "License:";
	const C_MANUAL = "Manual:";
	const C_MANUAL_FILE = "Manual File:";
	const C_NAME = "Name:";
	const C_PERMISSIONS = "Enable Permissions:";
	const C_NOTIFICATIONS = "Enable Notifications:";
	const C_IN_ROOT = "Copy of this module in root/modules:";
	const C_OPTIONS = "Options:";	
	const C_RELEASE = "Release:";
	const C_RELEASE_FILE = "Release File:";
	const C_RELEASE_INFO = "Release Info:";	
	const C_SEARCH = "Enable Search:";
	const C_STATUS = "Status:";
	const C_SUBMENU = "View Submenu:";	
	const C_SUBVERSION = "Subversion module:";
	const C_SUPPORT_URL = "Support URL:";
	const C_SUPPORT_NAME = "Support Name:";
	const C_UPLOAD_FILE = "Upload file:";
	const C_USER = "Visible User:";
	const C_VERSION = "Version:";
	const C_WEBSITE_URL = "Module Website URL:";
	const C_WEBSITE_NAME = "Module Website Name:";
	const C_MODULE_OR_EXTENSION = "Module or extension:";
	const C_MODULE_OR_EXTENSION_DESC = "If you choose to create an extension, the checkbox will be checked";
	
	const CH_NUMBER_ID = "N&176;&nbsp;ID";
	
	const CONF_ACTIVE_BLOCKS = "Allow Blocks";
	const CONF_ACTIVE_SEARCH = "Allow Search";
	const CONF_ACTIVE_COMMENTS = "Allow Comments";
	const CONF_ADMIN_PAGER = "Admin per page";
	const CONF_ADMIN_PAGER_DESC = "Set number of tables to view per page in admin.";
	const CONF_AUTHOR = "Module Author";
	const CONF_AUTHOR_EMAIL = "Author's Email";
	const CONF_AUTHOR_WEBSITE_URL = "Author's Website URL";
	const CONF_AUTHOR_WEBSITE_NAME = "Author's Website Name";
	const CONF_ACTIVE_NOTIFICATIONS = "Allow Notifications";
	const CONF_ACTIVE_PERMISSIONS = "Allow Permissions";
	const CONF_INROOT_COPY = "Copy this module also in root/modules";
	const CONF_BREAK_GENERAL = "General settings";
	const CONF_BREAK_MODULE = "Module settings";
	const CONF_CREDITS = "Credits";
	const CONF_DEMO_SITE_URL = "Demo Website URL";
	const CONF_DEMO_SITE_NAME = "Demo Website Name";
	const CONF_DESCRIPTION = "Module Description";
	const CONF_DISPLAY_ADMIN_SIDE = "Visible in Admin Panel";
	const CONF_DISPLAY_SUBMENU = "Display Submenu";
	const CONF_DISPLAY_USER_SIDE = "Visible in User side";	
	const CONF_EDITOR = "Editor";
	const CONF_EDITOR_DESC = "Select an editor to write";
	const CONF_IMAGE = "Modules Image";
	const CONF_IS_EXTENSION = "If the beginning is an extension, set Yes";
	const CONF_LICENSE = "License";
	const CONF_LICENSE_URL = "License URL";
	const CONF_MANUAL = "Modules Manual";
	const CONF_MANUAL_FILE = "Manual file";
	const CONF_MAX_UPLOAD_SIZE = "Maximum size of images";
	const CONF_MAX_UPLOAD_SIZE_DESC = "Set maximum size of images in Bytes";
	const CONF_MIMETYPES = "Mime Types";
	const CONF_MIMETYPES_DESC = "Mime Types for images";	
	const CONF_MODULE_DESCRIPTION = "Module Description";
	const CONF_NAME = "Module Name";	
	const CONF_DONATIONS = "Paypal Donations";
	const CONF_RELEASE_DATE = "Release Date";
	const CONF_RELEASE_INFO = "Modules Release Info";
	const CONF_RELEASE_FILE = "Module Release File";	
	const CONF_REVISION = "Svn Revision";
	const CONF_STATUS = "Module status";
	const CONF_SUPPORT_URL = "Support Website URL";
	const CONF_SUPPORT_NAME = "Support Website";
	const CONF_VERSION = "Module Version";
	const CONF_WEBSITE_URL = "Module website URL";
	const CONF_WEBSITE_NAME = "Module Website name";	
	
	const CONST_MODULES = "Select the module you want to build";
	const CONST_TABLES = "Select the table you want to build";

	const CONST_OK_ARCHITECTURE = "Structure of module (index.html, admin, icons, images, locale, templates, ...)";
	const CONST_OK_COMMENTS = "Created files for comments";
	const CONST_OK_DOCS = "Created <b>%s</b> file in the docs folder";
	const CONST_OK_CSS = "Created <b>%s</b> file in the css folder";
	const CONST_OK_ROOTS = "Created <b>%s</b> file in the root of this module";
	const CONST_OK_CLASSES = "Created <b>%s</b> file in the class folder";
	const CONST_OK_BLOCKS = "Created <b>%s</b> file in the blocks folder";
	const CONST_OK_SQL = "Created <b>%s</b> file in your sql folder";
	const CONST_OK_ADMINS = "Created <b>%s</b> file in the admin folder";
	const CONST_OK_LANGUAGES = "Created <b>%s</b> file in the locale folder";
	const CONST_OK_INCLUDES = "Created <b>%s</b> file in the include folder";
	const CONST_OK_TEMPLATES = "Created <b>%s</b> file in the templates folder";
	const CONST_OK_TEMPLATES_BLOCS = "Created <b>%s</b> file in the templates/blocks folder";
	const CONST_OK_TEMPLATES_ADMIN = "Created <b>%s</b> file in the templates/admin folder";

	const CONST_NOTOK_ARCHITECTURE = "Problems: Creating the module (index.html, icons ,...)";
	const CONST_NOTOK_COMMENTS = "Problems: Creating files for comments";
	const CONST_NOTOK_DOCS = "Problems: Creating <b>%s</b> file in the docs folder";
	const CONST_NOTOK_CSS = "Problems: Creating <b>%s</b> file in the css folder";
	const CONST_NOTOK_ROOTS = "Problems: Creating <b>%s</b> file in the root of this module";
	const CONST_NOTOK_CLASSES = "Problems: Creating <b>%s</b> file in your class folder";
	const CONST_NOTOK_BLOCKS = "Problems: Creating <b>%s</b> file in blocks folder";
	const CONST_NOTOK_SQL = "Problems: Creating <b>%s</b> file in sql folder";
	const CONST_NOTOK_ADMINS = "Problems: Creating <b>%s</b> file in the admin folder";
	const CONST_NOTOK_LANGUAGES = "Problems: Creating <b>%s</b> file in the locale folder";
	const CONST_NOTOK_INCLUDES = "Problems: Creating <b>%s</b> file in the include folder";
	const CONST_NOTOK_TEMPLATES = "Problems: Creating <b>%s</b> file in the templates folder";
	const CONST_NOTOK_TEMPLATES_BLOCS = "Problems: Creating <b>%s</b> file in the templates/blocks folder";
	const CONST_NOTOK_TEMPLATES_ADMIN = "Problems: Creating <b>%s</b> file in the templates/admin folder";
	
	const ALL_TABS_TIPS = "<ul><li>Add, update, create or delete modules, extensions, tables, fields, import old modules</li></ul>";
	
	const DISPLAY_ADMIN = "Visible Admin";
	const DISPLAY_USER = "Visible User";	

	const IMPORTANT = "Required Information";
	const NOT_IMPORTANT = "Optional Information";	
	
	const E_NO_EXTENSIONS = "There are no extensions";
	const E_NO_FIELDS = "There are no fields";
	const E_NO_FIELDS_FORM = "There are no form fields";
	const E_NO_MODULES = "There are no modules";
	const E_NO_TABLES = "There are no tables";
	
	const E_DATABASE_ERROR = "Database Error";	
	const E_DATABASE_SQL_FILE_NOT_IMPORTED = "Database Error: Not sql file or data entered!";
	const E_SQL_FILE_DATA_NOT_MATCH = "File Error: data in sql file do not match. Row: %s";
	const E_FILE_NOT_OPEN_READING = "File Error: Could not open file for reading!";
	const E_FILE_NOT_UPLOADING = "File Error: upload in file: %s";
	
	const FIELDS_NUMBER = "Number of fields";	
	
	const F_FILES_PATH = "Files in %s ";
	const F_EDIT = "Modification";
	const F_DEL = "Clear";
	const FORM_OK = "Form ok";
	const INFO_TABLE = "Information on the table";
	const INFO_TABLE_FIELD = "You can add your choice 3 fields in this table: '<b>table</b>'_submitter, '<b>table</b>'_created, '<b>table</b>'_online";

	const F_INDEX_NMTOTAL = "There are %s modules in the Database";
	const F_INDEX_NETOTAL = "There are %s extensions in the Database";
	const F_INDEX_NTTOTAL = "There are %s tables in the Database";
	const F_INDEX_NFTOTAL = "There are %s fields in the Database";
	const F_INDEX_NLTOTAL = "There are %s total defines locales in the Database";
	const F_INDEX_NITOTAL = "There are %s old modules imported in the Database";
	
	const INDEX_STATISTICS = "Statistics";
	const MODULE_IMPORTANT = "Required Information";
    const MODULE_NAME = "TDMCreate";
    const MODULE_DESC = "Module for creating others modules";
	const MODULE_INFORMATION = "Information";
	const MODULE_NOT_IMPORTANT = "Optional Information";	
	
	const MODULE_FIELDS_NUMBER = "Fields Number";
	const MODULE_BLOCKS = "Blocks";
	
	const QF_ARE_YOU_SURE_TO_DELETE = "Are you sure you want to delete: <span class='red bold'>%s</span>?";
	const QF_ARE_YOU_SURE_TO_RENEW = "Are you sure you want to renew: <span class='red bold'>%s</span>?";
	const QC_ISEXTENSION = "Is an Extension?";
	
	const S_SAVED = "Successfully saved";
	const S_DELETED = "Successfully deleted";
		
	const S_DELETED_SUCCESS	= "Deleted Successfully";
	const S_DATA_ENTERED = "Data entered successfull!";
		
	const TABLE_ADD = "Add a new table";
	const TABLE_EDIT = "Edit Table";
	const TABLE_MODULES = "Choose a module";
	const TABLE_NAME = "Table Name";
	const TABLE_NAME_DESC = "Unique Name for this Table";
	const TABLE_FIELDS_NUMBER = "Fields Number";
	const TABLE_FIELDS_NUMBER_DESC = "Number of fields for this table";
	const TABLE_FIELD_NAME = "Field Name";
	const TABLE_FIELD_NAME_DESC = "This is the prefix of field name (optional)<br />If you leave the field blank,<br />doesn't appear anything in the fields of the next screen,<br />otherwise you'll see all the fields with a prefix type (e.g: <span class='bold'>fieldname_</span>)";
	const TABLE_IMAGE = "Table Logo";
	const TABLE_BLOCKS = "Create blocks for this table";
	const TABLE_BLOCKS_DESC = "(blocs: random, latest, today)";
	const TABLE_DISPLAY_ADMIN = "Use the side view of Admin";
	const TABLE_DISPLAY_USER = "Use the side view of User";
	const TABLE_SUBMENU = "Use view TAB Submenu";
	const TABLE_SEARCH = "Active search for this table";
	const TABLE_EXIST = "The name specified for this table is already in use";
	const TABLE_COMMENTS = "Enable Comments for this table";

	const TABLE_ADMIN = "Display Admin";
	const TABLE_USER = "Display User";
	
	const TABLE_ID = "Id";
	const TABLE_ADMIN_LIST = "Display Admin";
	const TABLE_USER_LIST = "Display User";
	const TABLE_SUBMENU_LIST = "Display Submenu";
	const TABLE_SEARCH_LIST = "Active Search";
	const TABLE_COMMENTS_LIST = "Active Comments";
	const TABLE_NOTIFICATIONS_LIST = "Active Notifications";

	const TABLE_IMAGE_DESC = "<span class='red bold'>Attention</span>: If you want to choose a new image, is best to name it with the module name before and follow with the name of the image so as not to overwrite any images with the same name, in the <span class='bold'>Frameworks/moduleclasses/moduleadmin/icons/32/</span>. Otherwise an other solution, would be to insert the images in the module, a new folder is created, with the creation of the same module - <span class='bold'>images/32</span>.";

	const TABLE_NOTIFICATIONS = "Enable Notifications";
	const TABLE_ERROR_NOTABLES = "There are no tables";
	const TABLE_ERROR_NOMODULES = "There are no modules";
	
	const FIELD_ADD = "Add fields";
	const FIELD_EDIT = "Edit fields";
	const FIELD_NUMBER = "N&#176;";
	const FIELD_NAME = "Field Name";
	const FIELD_TYPE = "Type";
	const FIELD_VALUE = "Value";
	const FIELD_ATTRIBUTE = "Attribute";
	const FIELD_NULL = "Null";
	const FIELD_DEFAULT = "Default";
	const FIELD_KEY = "Key";
	const FIELD_AUTO_INCREMENT = " Auto Increment";

	const FIELD_OTHERS = "Others";
	const FIELD_ELEMENTS = "Options Elements";
	const C_FIELD_ELEMENT_NAME = "Form: Element";
	const C_FIELD_ADMIN = "Page: Show Admin Side";
	const C_FIELD_USER = "Page: Show User Side";
	const C_FIELD_BLOCK = "Block: View";
	const C_FIELD_MAINFIELD = "Table: Main Field";
	const C_FIELD_SEARCH = "Search: Index";
	const C_FIELD_REQUIRED = "Field: Required";
	const FIELD_ERROR_NOFIELDS = "There are no fields";
	
	const ADMIN_SUBMIT = "Send";
	
	const PERMISSIONS = "Permissions";
	const FORM_ON = "Online";
	const FORM_OFF = "Offline";
    const PERMISSIONS_ACCESS = "Permission to view";
	const PERMISSIONS_SUBMIT = "Permission to submit";	
	const PERMISSIONS_APPROVE = "Permission to approve";

	const BLOCK_DAY = "Today";
	const BLOCK_RANDOM = "Random";
	const BLOCK_RECENT = "Recent";

	const MIMETYPES = "Mime types authorized for:";
	const MIMESIZE = "Allowable size:";
	const C_EDITOR = "Editor:";

	const EXTENSIONS_LIST = "Extensions List";
	const EXTENSION = "Extension";
	
	const MODULES_LIST = "Modules List";
	const MODULE = "Module";
	
	const TABLES_LIST = "Tables List";
	const TABLE = "Table";
	
	const FIELDS_LIST = "Fields List";
	const FIELD = "Field";

	const NOT_MODULES = "<span class='red bold'>No module created, must create at least one before</span>";
	const MODULEADMIN_MISSING = "Module Admin Missing, Pleace! Install this Framework";
	const MODULE_DISPLAY_SUBMENU = "Visible Submenu";
	const MODULE_ACTIVE_NOTIFY = "Enable Notifications";
	const NOT_INSERTED = "<span class='red bold'>The module is not saved,<br />it is likely that you have used a name that already exists,<br />please change name for a new module.</span>";

	const DELETE = "Delete";
	const UPLOADS = "Uploads";
	const CF_IMAGE_PATH = "Image Path: %s ";
	const SUBMENU = "Submenu";  
	const SEARCH = "Search";  
	const COMMENTS = "Comments";  
	const NOTIFIES = "Notifies";

	const MISSING = "Error: You don&#39;t use the Frameworks \"admin module\". Please install this Frameworks";
	const F_MAINTAINEDBY = "<span class='bold green'>%s</span><span class='small italic'> is maintained by the </span><a href='%s' title='Visit %s' class='tooltip' rel='external'>%s</a><span class='small italic'> and by </span><a href='http://www.xoops.org/modules/newbb/' title='Visit Xoops Community' class='tooltip' rel='external'>Xoops Community</a>";

	const IMPORT = "Import";
	const IMPORT_OLD_MODULE = "Import old module";
	const IMPORTED = "Imported";
	const IMPORT_TITLE = "Form Import old module";
	const IMPORT_LIST = "List of old modules Imported";
	const IMPORTED_LIST = "Modules Imported List";

	const IMPORT_ID = "Id";
	const IMPORT_MID = "Module";
	const IMPORT_NAME = "Name";
	const IMPORT_TABLES_NUMBER = "Tables Number";
	const IMPORT_TABLE_NAME = "Table Name";
	const IMPORT_FIELDS_NUMBER = "Fields Number";
	const IMPORT_FIELD_NAME = "Field Name";
	const IMPORT_ERROR_NOIMPORTS = "There are not old modules imported";
	
	const LOCALE_TITLE = "Form locale";
	
	const LOCALE_ID = "Id";
	const LOCALE_MID = "Module";
	const LOCALE_FILE_NAME = "File Name";
	const LOCALE_DEFINE = "Tables Number";
	const LOCALE_DESCRIPTION = "Table Name";
	const LOCALE_ERROR_NOLOCALE = "There are not old defines imported";

	const BUILDING_TITLE = "Building";
	const BUILDING_MODULES = "Building modules";
	const BUILDING_EXTENSIONS = "Building Extensions";
	const BUILDING_SELECT_DEFAULT = "Select Module or Extension";
	const BUILDING_EXECUTED = "Build Executed";
	const BUILDING_SUCCESS = "Success";
	const BUILDING_ERROR = "Error";
	const BUILDING_FORM = "Building Form";	
}