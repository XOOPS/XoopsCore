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
 * maintenance extensions
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         maintenance
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage), Cointin Maxime (AKA Kraven30)
 * @version         $Id$
 */
define("_AM_MAINTENANCE_ACTIONS","Actions");
define("_AM_MAINTENANCE_AND", "AND");
define("_AM_MAINTENANCE_DELETE","Delete file");
define("_AM_MAINTENANCE_OR", "OR");

//index.php
define("_AM_MAINTENANCE_NBFILES","There are %s files");

//center.php
define("_AM_MAINTENANCE_CENTER_ANALYSE","Analyze");
define("_AM_MAINTENANCE_CENTER_CACHE","Clean cache folder");
define("_AM_MAINTENANCE_CENTER_CHECK","Check");
define("_AM_MAINTENANCE_CENTER_CHOICE1","Optimize table(s)");
define("_AM_MAINTENANCE_CENTER_CHOICE2","Check table(s)");
define("_AM_MAINTENANCE_CENTER_CHOICE3","Repair table(s)");
define("_AM_MAINTENANCE_CENTER_CHOICE4","Analyze table(s)");
define("_AM_MAINTENANCE_CENTER_OPTIMIZE","Optimize");
define("_AM_MAINTENANCE_CENTER_RESULT","Result");
define("_AM_MAINTENANCE_CENTER_RESULT_SESSION","Clean sessions table task successfully");
define("_AM_MAINTENANCE_CENTER_RESULT_SMARTY_CACHE","Cleaning the Smarty cache successfully (%s files)");
define("_AM_MAINTENANCE_CENTER_RESULT_SMARTY_COMPILE","Cleaning the Smarty compile successfully (%s files)");
define("_AM_MAINTENANCE_CENTER_RESULT_XOOPS_CACHE","Cleaning the Xoops cache successfully (%s files)");
define("_AM_MAINTENANCE_CENTER_RETURN","Return to maintenance center");
define("_AM_MAINTENANCE_CENTER_TABLES","Tables maintenance");
define("_AM_MAINTENANCE_CENTER_TABLES1","Tables");
define("_AM_MAINTENANCE_CENTER_TABLES_DESC",
"ANALYZE TABLE analyzes and stores the key distribution for a table. During the analysis, the table is locked with a read lock.<br />
CHECK TABLE checks a table or tables for errors.<br />
OPTIMIZE TABLE reclaims the unused space and to defragment the data file.<br />
REPAIR TABLE repairs a possibly corrupted table.");
define("_AM_MAINTENANCE_CENTER_REPAIR","Repair");
define("_AM_MAINTENANCE_CENTER_SESSION","Empty the sessions table");
define("_AM_MAINTENANCE_CENTER_SIZE","Size");
define("_AM_MAINTENANCE_CENTER_SIZE_SUFFIX","[KB]");
define("_AM_MAINTENANCE_CENTER_SMARTY_CACHE","Smarty cache");
define("_AM_MAINTENANCE_CENTER_SMARTY_COMPILE","Smarty compile");
define("_AM_MAINTENANCE_CENTER_XOOPS_CACHE","XOOPS cache");

//dump.php
define("_AM_MAINTENANCE_DUMP_DELETED","File deleted");
define("_AM_MAINTENANCE_DUMP_DELETEALL","Delete all files");
define("_AM_MAINTENANCE_DUMP_DELETEDALL","All files are deleted");
define("_AM_MAINTENANCE_DUMP_DOWNLOAD","Download");
define("_AM_MAINTENANCE_DUMP_DROP","Add command DROP TABLE IF EXISTS 'tables' in the dump");
define("_AM_MAINTENANCE_DUMP_ERROR_TABLES_OR_MODULES", "You must select the tables or modules");
define("_AM_MAINTENANCE_DUMP_FILES","Files");
define("_AM_MAINTENANCE_DUMP_FILE_CREATED", "File created");
define("_AM_MAINTENANCE_DUMP_FILE_NOTCREATED", "File not created");
define("_AM_MAINTENANCE_DUMP_FORM","Create new Dump");
define("_AM_MAINTENANCE_DUMP_LIST", "List of files");
define("_AM_MAINTENANCE_DUMP_NB_RECORDS", "Number of records");
define("_AM_MAINTENANCE_DUMP_NOFILE","No file");
define("_AM_MAINTENANCE_DUMP_NO_TABLES", "No tables");
define("_AM_MAINTENANCE_DUMP_STRUCTURES", "Structures");
define("_AM_MAINTENANCE_DUMP_TABLES", "Tables");
define("_AM_MAINTENANCE_DUMP_TABLES_OR_MODULES","Select tables or modules");
define("_AM_MAINTENANCE_DUMP_RECORDS", "record(s)");

// Tips
define("_AM_MAINTENANCE_CENTER_TIPS",
"<ul>
<li>You can do a simple maintenance of your XOOPS Installation: clear your cache and session table, and do maintenance of your tables</li>
</ul>");