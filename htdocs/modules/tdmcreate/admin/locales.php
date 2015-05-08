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
 * @author          XOOPS Development Team
 * @version         $Id: locales.php 10665 2012-12-27 10:14:15Z timgno $
 */
include __DIR__ . '/header.php';
// heaser
$xoops->header('admin:tdmcreate/tdmcreate_locales.tpl');
//
//$localeId = Request::getInt('import_id', 0);
//
$adminMenu->renderNavigation('locales.php');
//
switch ($op) 
{  	
	case 'list':     
	    $adminMenu->addItemButton(TDMCreateLocale::ADD_LOCALE, 'locales.php?op=new', 'add');            
		$adminMenu->renderButton();		
		// Get modules list
        $criteria = new CriteriaCompo();
        $criteria->setSort('loc_id');
        $criteria->setOrder('ASC'); 
        $criteria->setStart($start);
        $criteria->setLimit($limit);		
        $numLocale = $localeHandler->getCount($criteria);
		$localeArr = $localeHandler->getAll($criteria);
        // Assign Template variables
        $xoops->tpl()->assign('locales_count', $numLocale);		
		unset($criteria);          
        if ($numLocale > 0) {
            foreach (array_keys($localeArr) as $i) {
                $locale['id'] = $localeArr[$i]->getVar('loc_id');                 
                $locale['mid'] = $localeArr[$i]->getVar('loc_mid');
				$locale['name'] = $localeArr[$i]->getVar('loc_file');
				$locale['define'] = $localeArr[$i]->getVar('loc_define'); 
                $locale['description'] = $localeArr[$i]->getVar('loc_description');                
				$xoops->tpl()->append_by_ref('locales', $locale);
                unset($locale);				
            }
            // Display Page Navigation
			if ($numrows > $limit) {
				$nav = new XoopsPageNav($numrows, $limit, $start, 'start');
				$xoops->tpl()->assign('pagenav', $nav->renderNav(4));
			}
        } else {
            $xoops->tpl()->assign('error_message', TDMCreateLocale::IMPORT_ERROR_NOLOCALE);
        }	
    break;
    	 
	case 'new':
        $adminMenu->addItemButton(TDMCreateLocale::IMPORTED_LIST, 'locales.php', 'application-view-detail');
        $adminMenu->renderButton();

		$obj = $localeHandler->create();
        $form = $xoops->getModuleForm($obj, 'locales');
        $xoops->tpl()->assign('form', $form->render());	
	break;
	
	case 'save':
        if (!$xoops->security()->check()) {
			$xoops->redirect('modules.php', 3, implode(',', $xoops->security()->getErrors()));
		}
		
        if ($localeId > 0) {
            $obj = $localeHandler->get($localeId);
			//Form imported edited save		
			$obj->setVar('loc_mid', Request::getInt('loc_mid'));
			$obj->setVar('loc_file', Request::getString('loc_file'));
			$obj->setVar('loc_define', Request::getString('loc_define')); 	
			$obj->setVar('loc_description', Request::getString('loc_description'));
         	$xoops->redirect('locales.php', 3, TDMCreateLocale::E_DATABASE_SQL_FILE_NOT_IMPORTED);
		}			
		if ($localeHandler->insert($obj)) {
            $xoops->redirect('locales.php', 3, TDMCreateLocale::FORM_OK);
        }		

        $xoops->error($obj->getHtmlErrors());
        $form = $xoops->getModuleForm($obj, 'locales');
        $xoops->tpl()->assign('form', $form->render());
	break;
	
	case 'edit':
        $adminMenu->addItemButton(TDMCreateLocale::IMPORT_OLD_MODULE, 'locales.php?op=import', 'add');   
		$adminMenu->addItemButton(TDMCreateLocale::IMPORTED_LIST, 'locales.php', 'application-view-detail');
        $adminMenu->renderButton();		
		
		$obj = $localeHandler->get($localeId);
		$form = $xoops->getModuleForm($obj, 'locales');
		$xoops->tpl()->assign('form', $form->render());
	break;	
	
	case 'delete': 
        if ($localeId > 0) {
            $obj = $localeHandler->get($localeId);			
			if (isset($_POST['ok']) && $_POST['ok'] == 1) {
                if (!$xoops->security()->check()) {
                    $xoops->redirect('locales.php', 3, implode(',', $xoops->security()->getErrors()));
                }
                if ($localeHandler->delete($obj)) {
                    $xoops->redirect('locales.php', 2, sprintf(TDMCreateLocale::S_DELETED, TDMCreateLocale::IMPORT));
                } else {
                    $xoops->error($obj->getHtmlErrors());
                }
            } else {			
				$xoops->confirm(array('ok' => 1, 'id' => $localeId, 'op' => 'delete'), 'locales.php', sprintf(TDMCreateLocale::QF_ARE_YOU_SURE_TO_DELETE, $obj->getVar('loc_file')) . '<br />');
			}
		} else {
		    $xoops->redirect('locales.php', 1, TDMCreateLocale::E_DATABASE_ERROR);
		}	
    break;
}

include __DIR__ . '/footer.php';