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
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package	    tdmcreate
 * @since		2.6.0
 * @author 	    XOOPS Development Team
 * @version	    $Id common.php 10900 2013-01-19 13:00:30Z timgno $
**/
define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/media/xoops/images/icons/32');
define('TDMC_DIRNAME', basename(dirname(__DIR__)));
// Directory URL'
define('TDMC_URL', XOOPS_URL . '/modules/' . TDMC_DIRNAME);
define('TDMC_UPLOAD_URL', XOOPS_URL . '/uploads/' . TDMC_DIRNAME);
define('TDMC_UPLOAD_FILES_URL', TDMC_UPLOAD_URL . '/files');
define('TDMC_UPLOAD_IMAGES_URL', TDMC_UPLOAD_URL . '/images');
define('TDMC_UPLOAD_IMAGES_EXTENSIONS_URL', TDMC_UPLOAD_IMAGES_URL . '/extensions');
define('TDMC_UPLOAD_IMAGES_MODULES_URL', TDMC_UPLOAD_IMAGES_URL . '/modules');
define('TDMC_UPLOAD_IMAGES_TABLES_URL', TDMC_UPLOAD_IMAGES_URL . '/tables');
define('TDMC_UPLOAD_REPOSITORY_URL', TDMC_UPLOAD_URL . '/repository');
define('TDMC_UPLOAD_REPOSITORY_EXTENSIONS_URL', TDMC_UPLOAD_REPOSITORY_URL . '/extensions');
define('TDMC_UPLOAD_REPOSITORY_MODULES_URL', TDMC_UPLOAD_REPOSITORY_URL . '/modules');
// Directory PATH'
define('TDMC_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . TDMC_DIRNAME);
define('TDMC_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . TDMC_DIRNAME);
define('TDMC_UPLOAD_FILES_PATH', TDMC_UPLOAD_PATH . '/files');
define('TDMC_UPLOAD_IMAGES_PATH', TDMC_UPLOAD_PATH . '/images');
define('TDMC_UPLOAD_IMAGES_EXTENSIONS_PATH', TDMC_UPLOAD_IMAGES_PATH . '/extensions');
define('TDMC_UPLOAD_IMAGES_MODULES_PATH', TDMC_UPLOAD_IMAGES_PATH . '/modules');
define('TDMC_UPLOAD_IMAGES_TABLES_PATH', TDMC_UPLOAD_IMAGES_PATH . '/tables');
define('TDMC_UPLOAD_REPOSITORY_PATH', TDMC_UPLOAD_PATH . '/repository');
define('TDMC_UPLOAD_REPOSITORY_EXTENSIONS_PATH', TDMC_UPLOAD_REPOSITORY_PATH . '/extensions');
define('TDMC_UPLOAD_REPOSITORY_MODULES_PATH', TDMC_UPLOAD_REPOSITORY_PATH . '/modules');