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
 * @version         $Id: install.php 10665 2012-12-27 10:14:15Z timgno $
 */

function xoops_module_install_tdmcreate($module)
{
    $xoops = Xoops::getInstance();
    $xoops->loadLanguage('modinfo');
    $xoops->registry()->set('tdmcreate_id', $module->getVar('mid'));
		
    $indexFile = XOOPS_UPLOAD_PATH.'/index.html';
	$blankFile = XOOPS_UPLOAD_PATH.'/blank.gif';
	
	//Creation of folder 'uploads/tdmcreate'
	$tdmcreate = XOOPS_UPLOAD_PATH.'/tdmcreate';
	if(!is_dir($tdmcreate)) {
		mkdir($tdmcreate, 0777);
		chmod($tdmcreate, 0777);
	}
	copy($indexFile, $tdmcreate.'/index.html');
	
	//Creation of the 'files' folder in uploads
	$files_uploads = $tdmcreate.'/files';
	if(!is_dir($files_uploads)) {
		mkdir($files_uploads, 0777);
		chmod($files_uploads, 0777);
	}
	copy($indexFile, $files_uploads.'/index.html');
	
	//Creation of the 'repository' folder in uploads
	$repository = $tdmcreate.'/repository';
	if(!is_dir($repository)) {
		mkdir($repository, 0777);
		chmod($repository, 0777);
	}
	copy($indexFile, $repository.'/index.html');
	
	//Creation of the 'repository/extensions' folder in uploads
	$extensions = $repository.'/extensions';
	if(!is_dir($extensions)) {
		mkdir($extensions, 0777);
		chmod($extensions, 0777);
	}
	copy($indexFile, $extensions.'/index.html');
	
	//Creation of the 'repository/modules' folder in uploads
	$modules = $repository.'/modules';
	if(!is_dir($modules)) {
		mkdir($modules, 0777);
		chmod($modules, 0777);
	}
	copy($indexFile, $modules.'/index.html');
	
	//Creation of the 'images' folder in uploads
	$images = $tdmcreate.'/images';
	if(!is_dir($images)) {
		mkdir($images, 0777);
		chmod($images, 0777);
	}
	copy($indexFile, $images.'/index.html');
	copy($blankFile, $images.'/blank.gif');	
	
	//Creation of the 'images/modules' folder in uploads
	$modules = $images.'/modules';
	$default = TDMC_ROOT_PATH.'/assets/images/default.png';
	$naked   = TDMC_ROOT_PATH.'/assets/images/naked.png';
	if(!is_dir($modules)) {
		mkdir($modules, 0777);
		chmod($modules, 0777);
	}
	copy($indexFile, $modules.'/index.html');
	copy($blankFile, $modules.'/blank.gif');
	copy($naked, $modules.'/naked.png');
	copy($default, $modules.'/default_slogo.png');
	
	//Creation of the folder 'images/tables' in uploads
	$tables = $images.'/tables';
	if(!is_dir($tables)) {
		mkdir($tables, 0777);
		chmod($tables, 0777);
	}
	copy($indexFile, $tables.'/index.html');	
	copy($blankFile, $tables.'/blank.gif');
	
	//Creation of the folder 'images/extensions' in uploads
	$extensions = $images.'/extensions';
	if(!is_dir($extensions)) {
		mkdir($extensions, 0777);
		chmod($extensions, 0777);
	}
	copy($indexFile, $extensions.'/index.html');
	copy($blankFile, $extensions.'/blank.gif');
	
    return true;
}