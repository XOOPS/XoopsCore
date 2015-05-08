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
include __DIR__ . '/header.php';
// Preferences Limit
//$tableId = Request::getInt('table_id', 0);
// header
$xoops->header('admin:tdmcreate/tdmcreate_tables.tpl');
//
$adminMenu->renderNavigation('tables.php');
//
switch ($op) 
{   
    case 'list': 
    default:               
	    $adminMenu->addItemButton(TDMCreateLocale::ADD_TABLE, 'tables.php?op=new', 'add');            
	    $adminMenu->renderButton();        
		// Get modules list
        $criteria = new CriteriaCompo();
        $criteria->setSort('mod_name');
        $criteria->setOrder('ASC'); 
        $criteria->setStart($start);
        $criteria->setLimit($limit);		
        $numRowsMods = $modulesHandler->getCount($criteria);
		$moduleArray = $modulesHandler->getAll($criteria);
		$xoops->tpl()->assign('modules_count', $numRowsMods);
		$xoops->tpl()->assign('mimg_path', TDMC_MODULES_URL_IMG);
		unset($criteria);
		// Redirect if there aren't modules
        /*if ( $numRowsMods == 0 ) {
            $xoops->redirect('modules.php?op=new', 2, TDMCreateLocale::NOTMODULES );
        }*/                	
        // Assign Template variables
        $xoops->tpl()->assign('mods_count', $numRowsMods);		
        if ($numRowsMods > 0) {
            foreach (array_keys($moduleArray) as $i) {
                $mod['id']            = $moduleArray[$i]->getVar('mod_id');
                $mod['name']          = $moduleArray[$i]->getVar('mod_name'); 
				$mod['image']         = $moduleArray[$i]->getVar('mod_image');
                $mod['admin']         = $moduleArray[$i]->getVar('mod_admin');
                $mod['user']          = $moduleArray[$i]->getVar('mod_user'); 
				$mod['submenu']       = $moduleArray[$i]->getVar('mod_submenu');  
                $mod['search']        = $moduleArray[$i]->getVar('mod_search');
                $mod['comments']      = $moduleArray[$i]->getVar('mod_comments'); 
                $mod['notifications'] = $moduleArray[$i]->getVar('mod_notifications');            
				/*if (file_exists($timage = XOOPS_URL ."/uploads/tdmcreate/images/tables/".$table_image)) {
					$table['image'] =  $timage; 
				} elseif (file_exists($timage = XOOPS_URL ."/media/xoops/images/icons/32/".$table_image)) { 
					$table['image'] =  $timage;
				}*/	
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('table_mid', $i));
				$criteria->setSort('table_name');
				$criteria->setOrder('ASC');	
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$numRowsTables = $tablesHandler->getCount($criteria);
				$tablesArray   = $tablesHandler->getAll($criteria);	
				$xoops->tpl()->assign('tables_count', $numRowsTables);	
				$xoops->tpl()->assign('timg_path', TDMC_TABLES_URL_IMG);
				unset($criteria);
                $tables = array();				
				if ($numRowsTables > 0) {
					foreach (array_keys($tablesArray) as $i)
					{
						$table['id'] 			= $tablesArray[$i]->getVar('table_id');
						$table['name'] 			= $tablesArray[$i]->getVar('table_name');											
						$table['image'] 		= $tablesArray[$i]->getVar('table_image');					
						$table['nbfields'] 		= $tablesArray[$i]->getVar('table_nbfields'); 
						$table['blocks'] 		= $tablesArray[$i]->getVar('table_blocks');
						$table['admin'] 		= $tablesArray[$i]->getVar('table_admin');                
						$table['user'] 			= $tablesArray[$i]->getVar('table_user'); 
						$table['submenu'] 		= $tablesArray[$i]->getVar('table_submenu');	
						$table['search'] 		= $tablesArray[$i]->getVar('table_search');                
						$table['comments'] 		= $tablesArray[$i]->getVar('table_comments'); 
						$table['notifications'] = $tablesArray[$i]->getVar('table_notifications');						
						//$xoops->tpl()->append_by_ref('tables', $table);
						$tables[] = $table;
						unset($table);
					}
				}
				$mod['tables'] = $tables;	
				$xoops->tpl()->append_by_ref('modules', $mod);
                unset($mod);
			}
            // Display Page Navigation
			if ($numRowsMods > $limit) {
				$nav = new XoopsPageNav($numRowsMods, $limit, $start, 'start');
				$xoops->tpl()->assign('pagenav', $nav->renderNav(4));
			}
        } else {
            $xoops->tpl()->assign('error_message', TDMCreateLocale::TABLE_ERROR_NOMODULES);
        }		
    break;

    case 'new':        
        $adminMenu->addItemButton(TDMCreateLocale::TABLES_LIST, 'tables.php', 'application-view-detail');
        $adminMenu->renderButton();
        	
		$tablesObj  = $tablesHandler->create($tableId);
        $form 		= $xoops->getModuleForm($tablesObj, 'tables');
        $xoops->tpl()->assign('form', $form->render());
    break;	
		
	case 'save':		
		if (!$xoops->security()->check()) {
			$xoops->redirect('tables.php', 3, implode(',', $xoops->security()->getErrors()));
		}
		
        if ($tableId > 0) {
            $tablesObj = $tablesHandler->get($tableId);
        } else {
            $tablesObj = $tablesHandler->create();
        }
		$tableMid 	     = Request::getInt('table_mid', 0);
		$tableNumbFields = Request::getInt('table_nbfields', 0);
		$tableFieldname  = Request::getStr('table_fieldname', '');
		//Form tables		
		$tablesObj->setVars(array('table_mid' 		=> $tableMid, 
								'table_name' 		=> $_POST['table_name'], 
								'table_nbfields' 	=> $tableNumbFields, 
								'table_fieldname' 	=> $tableFieldname));
		//Form table_image
	    $uploaddir  = ( is_dir(XOOPS_ICONS32_PATH) && XoopsLoad::fileExists(XOOPS_ICONS32_PATH) ) ? XOOPS_ICONS32_PATH : TDMC_TABLES_PATH_IMG;	
        $uploader   = new XoopsMediaUploader( $uploaddir, $xoops->getModuleConfig('mimetypes'), 
											   $xoops->getModuleConfig('maxuploadsize'), null, null);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
		    $extension = preg_replace( '/^.+\.([^.]+)$/sU' , '\\1' , $_FILES['attachedfile']['name']);
            $imgName = $_GET['table_name'].'.'.$extension;
			$uploader->setPrefix($imgName);
			$uploader->fetchMedia($_POST['xoops_upload_file'][0]);
			if (!$uploader->upload()) {
				$xoops->redirect('javascript:history.go(-1)',3, $uploader->getErrors());
			} else {
				$tablesObj->setVar('table_image', $uploader->getSavedFileName());
			}
		} else {
			if ($_POST['tables_image'] == 'blank.gif') {
                $tablesObj->setVar('table_image', $_POST['table_image']);
            } else {
                $tablesObj->setVar('table_image', $_POST['tables_image']);
            }
		}		
		//Form tables
		$tablesObj->setVars(array('table_blocks'    	=> Request::getInt('table_blocks', 0), 
								'table_admin' 			=> Request::getInt('table_admin', 0), 
								'table_user' 			=> Request::getInt('table_user', 0), 
								'table_submenu' 		=> Request::getInt('table_submenu', 0), 
								'table_search' 			=> Request::getInt('table_search', 0), 
								'table_comments' 		=> Request::getInt('table_comments', 0), 
								'table_notifications' 	=> Request::getInt('table_notifications', 0)));
				
        if( $tablesHandler->insert($tablesObj) ) {	 
			if( $tablesObj->isNew() ) {
				$tid = $xoops->db()->getInsertId();			
				$xoops->redirect('fields.php?op=new&amp;field_mid='.$mid.'&amp;field_tid='.$tid.'&amp;field_numb='.$tableNumbFields.'&amp;field_name='.$tableFieldname, 3, XoopsLocale::S_DATA_INSERTED);			
			} else {
				$xoops->redirect('tables.php', 3, XoopsLocale::S_DATABASE_UPDATED);
			}
		}

        $xoops->error($tablesObj->getHtmlErrors());
        $form = $xoops->getModuleForm($tablesObj, 'tables');
        $xoops->tpl()->assign('form', $form->render());
	break;
	
	case 'edit':       
		$adminMenu->addItemButton(TDMCreateLocale::TABLE_ADD, 'tables.php?op=new', 'add');
		$adminMenu->addItemButton(TDMCreateLocale::TABLES_LIST, 'tables.php', 'application-view-detail');
        $adminMenu->renderButton();			

		$tablesObj = $tablesHandler->get($tableId);
		$form = $xoops->getModuleForm($tablesObj, 'tables');
		$xoops->tpl()->assign('form', $form->render());        
	break;	
		
	case 'delete':	        	
        if ($tableId > 0) {
            $tablesObj = $tablesHandler->get($tableId);			
			if (isset($_POST['ok']) && $_POST['ok'] == 1) {
                if (!$xoops->security()->check()) {
                    $xoops->redirect('tables.php', 3, implode(',', $xoops->security()->getErrors()));
                }
                if ($tablesHandler->delete($tablesObj)) {
                    $xoops->redirect('tables.php', 2, sprintf(TDMCreateLocale::S_DELETED, TDMCreateLocale::TABLE));
                } else {
                    $xoops->error($tablesObj->getHtmlErrors());
                }
            } else {			
				$xoops->confirm(array('ok' => 1, 'id' => $tableId, 'op' => 'delete'), 'tables.php', sprintf(TDMCreateLocale::QF_ARE_YOU_SURE_TO_DELETE, $tablesObj->getVar('table_name')) . '<br />');
			}
		} else {
		    $xoops->redirect('tables.php', 1, TDMCreateLocale::E_DATABASE_ERROR);
		}
	break; 	
}

include __DIR__ . '/footer.php';