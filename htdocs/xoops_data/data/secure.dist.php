<?php
/**
 * XOOPS secure file
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version     $Id$
 */

// Database
// Choose the database to be used
define("XOOPS_DB_TYPE", "mysql");

// Set the database charset if applicable
define("XOOPS_DB_CHARSET", "");

// Table Prefix
// This prefix will be added to all new tables created to avoid name conflict in the database. If you are unsure, just use the default "xoops".
define("XOOPS_DB_PREFIX", "xoops");

// Database Hostname
// Hostname of the database server. If you are unsure, "localhost" works in most cases.
define("XOOPS_DB_HOST", "localhost");

// Database Username
// Your database user account on the host
define("XOOPS_DB_USER", "");

// Database Password
// Password for your database user account
define("XOOPS_DB_PASS", "");

// Database Name
// The name of database on the host.
define("XOOPS_DB_NAME", "");

// Use persistent connection? (Yes=1 No=0)
// Default is "No". Choose "No" if you are unsure.
define("XOOPS_DB_PCONNECT", 0);

// Serialized connection parameter
// This is built by the installer and includes all connection parameters
define("XOOPS_DB_PARAMETERS", "");
