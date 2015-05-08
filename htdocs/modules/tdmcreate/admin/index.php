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
 * @version         $Id: index.php 10665 2012-12-27 10:14:15Z timgno $
 */
include __DIR__ . '/header.php';
// header
$xoops->header();
// tdmcreate modules
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('mod_id', 0, '!='));
$modules = $modulesHandler->getCount($criteria);
unset($criteria);
// tdmcreate tables
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('table_mid', 0, '!='));
$tables = $tablesHandler->getCount($criteria);
unset($criteria);
// tdmcreate tables
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('field_mid', 0, '!='));
$criteria->add(new Criteria('field_tid', 0, '!='));
$fields = $fieldsHandler->getCount($criteria);
unset($criteria);
// tdmcreate modules
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_mid', 0, '!='));
$locale = $localeHandler->getCount($criteria);
unset($criteria);
// tdmcreate import
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('import_id', 0, '!='));
$import = $importHandler->getCount($criteria);
unset($criteria);
$r = "red"; $g = "green";
$modulesColor = $modules == 0 ? $r : $g;
$tablesColor = $tables == 0 ? $r : $g;
$fieldsColor = $fields == 0 ? $r : $g;
$localeColor = $locale == 0 ? $r : $g;
$importColor = $import == 0 ? $r : $g;

$adminMenu->displayNavigation('index.php');

$adminMenu->addInfoBox(TDMCreateLocale::INDEX_STATISTICS);
$adminMenu->addInfoBoxLine(sprintf(TDMCreateLocale::F_INDEX_NMTOTAL, '<span class="'.$modulesColor.'">' . $modules . '</span>'));
$adminMenu->addInfoBoxLine(sprintf(TDMCreateLocale::F_INDEX_NTTOTAL, '<span class="'.$tablesColor.'">' . $tables . '</span>'));
$adminMenu->addInfoBoxLine(sprintf(TDMCreateLocale::F_INDEX_NFTOTAL, '<span class="'.$fieldsColor.'">' . $fields . '</span>'));
$adminMenu->addInfoBoxLine(sprintf(TDMCreateLocale::F_INDEX_NLTOTAL, '<span class="'.$localeColor.'">' . $locale . '</span>'));
$adminMenu->addInfoBoxLine(sprintf(TDMCreateLocale::F_INDEX_NITOTAL, '<span class="'.$importColor.'">' . $import . '</span>'));

// folder path
$folderPath = array(
				TDMC_UPLOAD_PATH,
				TDMC_UPLOAD_FILES_PATH,
				TDMC_UPLOAD_REPOSITORY_PATH,
				TDMC_UPLOAD_REPOSITORY_EXTENSIONS_PATH,
				TDMC_UPLOAD_REPOSITORY_MODULES_PATH,
				TDMC_UPLOAD_IMAGES_PATH,
				TDMC_UPLOAD_IMAGES_EXTENSIONS_PATH,
				TDMC_UPLOAD_IMAGES_MODULES_PATH,
				TDMC_UPLOAD_IMAGES_TABLES_PATH
			);
foreach ($folderPath as $folder) {
	$adminMenu->addConfigBoxLine($folder, 'folder');
	$adminMenu->addConfigBoxLine(array($folder, '777'), 'chmod');
}
$adminMenu->addConfigBoxLine('thumbnail', 'service');
// extension
$extensions = array('xtranslator' => 'extension');

foreach ($extensions as $module => $type) {
    $adminMenu->addConfigBoxLine(array($module, 'warning'), $type);
}

$adminMenu->displayIndex();

include __DIR__ . '/footer.php';
