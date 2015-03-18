<?php
/**
 * This is a dummy placeholder for mainfile.php, the XOOPS configuration file
 *
 * This is only needed for installation on servers that cannot write a file directly
 * into the main XOOPS root directory. On these servers, the install will stop
 * progressing after configuring the database (install/page_dbconnection.php)
 *
 * If you experience this problem:
 *  - copy this file to the XOOPS root directory
 *  - set the file permissions to make it writeable
 *  - rerun the install
 *
 * Typical writable permission would be 665 (user read/write, group read/write, other read)
 * If problems persist, escalate permission to 777 (world read/write/execute) as a last resort.
 */
if (! defined('XOOPS_INSTALL')) {
    header('Location: install/index.php');
}
