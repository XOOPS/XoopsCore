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
 * @version     $Id: modules.php 10665 2012-12-27 10:14:15Z timgno $
 */
include __DIR__ . '/header.php';
// Get Action type
//$modId = Request::getInt('mod_id');
// Get handler
$xoops->header('admin:tdmcreate/tdmcreate_modules.tpl');
$adminMenu->renderNavigation('modules.php');
switch ($op) 
{   
    case 'list': 
    default:	
		$adminMenu->addItemButton(TDMCreateLocale::ADD_MODULE, 'modules.php?op=new', 'add');            
		$adminMenu->renderButton();
		// Get modules list
        $criteria = new CriteriaCompo();
        $criteria->setSort('mod_name');
        $criteria->setOrder('ASC'); 
        $criteria->setStart($start);
        $criteria->setLimit($limit);		
        $numRowsMods  = $modulesHandler->getCount($criteria);
		$modulesArray = $modulesHandler->getAll($criteria);
        // Assign Template variables
        $xoops->tpl()->assign('modules_count', $numRowsMods);		
		unset($criteria);          
        if ($numRowsMods > 0) {
            foreach (array_keys($modulesArray) as $i) {
                $mod['id'] = $modulesArray[$i]->getVar('mod_id');
                $mod['name'] = $modulesArray[$i]->getVar('mod_name'); 
                $mod['version'] = $modulesArray[$i]->getVar('mod_version');
				$mod['image'] = $modulesArray[$i]->getVar('mod_image');
				$mod['release'] = $modulesArray[$i]->getVar('mod_release'); 
                $mod['status'] = $modulesArray[$i]->getVar('mod_status'); 
                $mod['admin'] = $modulesArray[$i]->getVar('mod_admin');
                $mod['user'] = $modulesArray[$i]->getVar('mod_user'); 
				$mod['submenu'] = $modulesArray[$i]->getVar('mod_submenu');  
                $mod['search'] = $modulesArray[$i]->getVar('mod_search');
                $mod['comments'] = $modulesArray[$i]->getVar('mod_comments'); 
                $mod['notifications'] = $modulesArray[$i]->getVar('mod_notifications');
				$xoops->tpl()->append_by_ref('modules', $mod);
                unset($mod);				
            }
            // Display Page Navigation
			if ($numRowsMods > $limit) {
				$nav = new XoopsPageNav($numRowsMods, $limit, $start, 'start');
				$xoops->tpl()->assign('pagenav', $nav->renderNav(4));
			}
        } else {
            $xoops->tpl()->assign('error_message', TDMCreateLocale::E_NO_MODULES);
        }		
    break;

    case 'new':        
        $adminMenu->addItemButton(TDMCreateLocale::MODULES_LIST, 'modules.php', 'application-view-detail');
        $adminMenu->renderButton();
        
		$modulesObj = $modulesHandler->create();
        $form 		= $xoops->getModuleForm($modulesObj, 'modules');
        $xoops->tpl()->assign('form', $form->render());
    break;	
	
	case 'save':
		if (!$xoops->security()->check()) {
			$xoops->redirect('modules.php', 3, implode(',', $xoops->security()->getErrors()));
		}
		$modId = Request::getInt('mod_id', 0);
        if ($modId > 0) {
            $modulesObj = $modulesHandler->get($modId);
        } else {
            $modulesObj = $modulesHandler->create();
        }        	
		//Form module save		
		$modulesObj->setVars(array('mod_name' 				=> Request::getString('mod_name', ''), 
								'mod_isextension' 			=> Request::getInt('mod_isextension', 0), 
								'mod_version' 				=> Request::getString('mod_version', ''), 
								'mod_description' 			=> Request::getString('mod_description', ''), 
								'mod_author' 				=> Request::getString('mod_author', ''), 
								'mod_author_mail' 			=> Request::getString('mod_author_mail', ''), 
								'mod_author_website_url' 	=> Request::getString('mod_author_website_url', ''), 
								'mod_author_website_name' 	=> Request::getString('mod_author_website_name', ''), 
								'mod_credits' 				=> Request::getString('mod_credits', ''), 
								'mod_license' 				=> Request::getString('mod_license', ''), 
								'mod_release_info' 			=> Request::getString('mod_release_info', ''), 
								'mod_release_file' 			=> Request::getString('mod_release_file', ''), 
								'mod_manual' 				=> Request::getString('mod_manual', ''), 
								'mod_manual_file' 			=> Request::getString('mod_manual_file', '')));		
		//Form module_image	       
        $uploader = new XoopsMediaUploader( TDMC_MODULES_PATH_IMG, $helper->getConfig('mimetypes'), 
											    $helper->getConfig('maxuploadsize'), null, null);
		if ($uploader->fetchMedia('xoops_upload_file')) {
		    $extension = preg_replace( "/^.+\.([^.]+)$/sU" , "\\1" , $_FILES['xoops_upload_file']['name']);
            $imageName = 'logo.'.$extension;
			$uploader->setPrefix($imageName);
			$uploader->fetchMedia('xoops_upload_file');
			if (!$uploader->upload()) {
				$xoops->redirect('javascript:history.go(-1)',3, $uploader->getErrors());
			} else {
				$modulesObj->setVar('mod_image', $uploader->getSavedFileName());
			}
		} else {
			if ($_POST['modules_image'] == 'blank.gif') {
                $modulesObj->setVar('mod_image', $_POST['mod_image']);
            } else {
                $modulesObj->setVar('mod_image', $_POST['modules_image']);
            }
		}
		
        //Form module save		
		$modulesObj->setVars(array('mod_demo_site_url' 		=> Request::getString('mod_demo_site_url', ''), 
								'mod_demo_site_name' 		=> Request::getString('mod_demo_site_name', ''), 
								'mod_support_url' 			=> Request::getString('mod_support_url', ''), 
								'mod_support_name' 			=> Request::getString('mod_support_name', ''), 
								'mod_website_url' 			=> Request::getString('mod_website_url', ''), 
								'mod_website_name' 			=> Request::getString('mod_website_name', ''), 
								'mod_release' 				=> Request::getString('mod_release', ''), 
								'mod_status' 				=> Request::getString('mod_status', ''),
								'mod_admin' 				=> Request::getInt('mod_admin', 0), 
								'mod_user' 					=> Request::getInt('mod_user', 0), 
								'mod_submenu' 				=> Request::getInt('mod_submenu', 0), 
								'mod_search' 				=> Request::getInt('mod_search', 0), 
								'mod_comments' 				=> Request::getInt('mod_comments', 0), 
								'mod_notifications' 		=> Request::getInt('mod_notifications', 0), 
								'mod_paypal' 				=> Request::getString('mod_paypal', ''), 
								'mod_subversion' 			=> Request::getString('mod_subversion', '')));
		// Insert Data		
        if ($modulesHandler->insert($modulesObj)) {
            $xoops->redirect('modules.php', 2, XoopsLocale::S_DATA_INSERTED);
        }
        // Form Data
        $xoops->error($modulesObj->getHtmlErrors());
        $form = $xoops->getModuleForm($modulesObj, 'modules');
        $xoops->tpl()->assign('form', $form->render());
	break;
	
	case 'edit':	    
        $adminMenu->addItemButton(TDMCreateLocale::ADD_MODULE, 'modules.php?op=new', 'add');
		$adminMenu->addItemButton(TDMCreateLocale::MODULES_LIST, 'modules.php', 'application-view-detail');
        $adminMenu->renderButton();		
		$modId = Request::getInt('mod_id', 0);
		if ($modId > 0) {
			$modulesObj = $modulesHandler->get($modId);
			$form 		= $helper->getForm($modulesObj, 'modules');
			$xoops->tpl()->assign('form', $form->render());
		} else {
            $xoops->redirect('modules.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
	break;
	
	case 'delete':	
        $modId = Request::getInt('mod_id', 0);	
		if ($modId > 0) {
			$modulesObj = $modulesHandler->get($modId);			
			if (isset($_POST['ok']) && $_POST['ok'] == 1) {
				if (!$xoops->security()->check()) {
					$xoops->redirect('modules.php', 3, implode(',', $xoops->security()->getErrors()));
				}
				if ($modulesHandler->delete($modulesObj)) {
					$xoops->redirect('modules.php', 2, sprintf(TDMCreateLocale::S_DELETED, TDMCreateLocale::MODULE));
				} else {
					$xoops->error($modulesObj->getHtmlErrors());
				}
			} else {			
				$xoops->confirm(array('ok' => 1, 'mod_id' => $modId, 'op' => 'delete'), 'modules.php', sprintf(TDMCreateLocale::QF_ARE_YOU_SURE_TO_DELETE, $modulesObj->getVar('mod_name')) . '<br />');
			}
        } else {
            $xoops->redirect('modules.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }		
	break;	
}
$xoops->footer();