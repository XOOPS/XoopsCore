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
 * @version         $Id: fields.php 10665 2012-12-27 10:14:15Z timgno $
 */
include __DIR__ . '/header.php';
/*
$fieldId = Request::getInt('field_id');
$fieldMid = Request::getInt('field_mid');
$fieldTid = Request::getInt('field_tid');	
$fieldNumb = Request::getInt('field_numb');
$fieldName = Request::getString('field_name', '');*/

// Get handler
$xoops->header('admin:tdmcreate/tdmcreate_fields.tpl');

$adminMenu->renderNavigation('fields.php');
switch ($op) 
{   
    case 'list': 
    default:               
	    $adminMenu->addItemButton(TDMCreateLocale::ADD_TABLE, 'tables.php?op=new', 'add');            
	    $adminMenu->renderButton(); 
        // Get modules list
        $criteria = new CriteriaCompo(new Criteria('table_mid', $fieldMid));
		$criteria->add(new Criteria('table_id', $fieldTid));
        $criteria->setSort('table_name');
        $criteria->setOrder('ASC'); 
        $criteria->setStart($start);
        $criteria->setLimit($limit);		
        $numRowsTables = $tablesHandler->getCount($criteria);
		$tablesArray = $tablesHandler->getAll($criteria);
		$xoops->tpl()->assign('tables_count', $numRowsTables);
		unset($criteria);
		// Redirect if there aren't modules
        if ( $numRowsTables == 0 ) {
            $xoops->redirect('tables.php?op=new', 2, TDMCreateLocale::E_NO_TABLES );
        }			                	
        // Assign Template variables
        $xoops->tpl()->assign('fields_list', true);
		$xoops->tpl()->assign('fields_count', $numRowsFields);		
        if ($numRowsTables > 0) {
            foreach (array_keys($tablesArray) as $i) {                
                $tables['id'] = $tablesArray[$i]->getVar('table_id');
				$tables['name'] = $tablesArray[$i]->getVar('table_name');				
				$module_name = $modules_Handler->get($tablesArray[$i]->getVar('table_mid'));
				$tables['mid'] = $module_name->getVar('mod_name');
				$tables['image'] = $tablesArray[$i]->getVar('table_image');                
				$tables['nbfields'] = $tablesArray[$i]->getVar('table_nbfields'); 
				$tables['blocks'] = $tablesArray[$i]->getVar('table_blocks');
				$tables['admin'] = $tablesArray[$i]->getVar('table_admin');                
				$tables['user'] = $tablesArray[$i]->getVar('table_user'); 
				$tables['submenu'] = $tablesArray[$i]->getVar('table_submenu');	
				$tables['search'] = $tablesArray[$i]->getVar('table_search');                
				$tables['comments'] = $tablesArray[$i]->getVar('table_comments'); 
				$tables['notifications'] = $tablesArray[$i]->getVar('table_notifications');	                
				$xoops->tpl()->append_by_ref('tables', $tables);
				unset($tables);				
            }
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('field_tid', $fieldTid));
			$criteria->setSort('field_id');
			$criteria->setOrder('ASC');	
			$numRowsFields = $fieldsHandler->getCount($criteria);
			$fieldsArray = $fieldsHandler->getAll($criteria);		
			unset($criteria);
			if ($numRowsFields > 0) {
				foreach (array_keys($fieldsArray) as $i) {
					$field['id'] = $fieldsArray[$i]->getVar('field_id');
					$table_name = $tablesHandler->get($fieldsArray[$i]->getVar('field_tid'));
					$field['tid'] = $table_name->getVar('table_name');				
					$field['name'] = $fieldsArray[$i]->getVar('field_name');
					$field['type'] = $fieldsArray[$i]->getVar('field_type');                
					$field['value'] = $fieldsArray[$i]->getVar('field_value');  
					$field['blocks'] = $fieldsArray[$i]->getVar('field_blocks');
					$field['attribute'] = $fieldsArray[$i]->getVar('field_attribute');
					$field['default'] = $fieldsArray[$i]->getVar('field_default');
					$field['key'] = $fieldsArray[$i]->getVar('field_key');					
					$field['blocks'] = $fieldsArray[$i]->getVar('field_blocks');
					$field['search'] = $fieldsArray[$i]->getVar('field_search');                     
					$field['required'] = $fieldsArray[$i]->getVar('field_required');						
					$xoops->tpl()->append_by_ref('fields', $field);
					unset($field);
				}
			}
            // Display Page Navigation
			if ($numRowsTables > $limit) {
				$nav = new XoopsPageNav($numRowsTables, $limit, $start, 'start');
				$xoops->tpl()->assign('pagenav', $nav->renderNav(4));
			}
        } else {
            $xoops->tpl()->assign('error_message', TDMCreateLocale::FIELD_ERROR_NOFIELDS);
        }				
    break;
	
    case 'new':     
        $adminMenu->addItemButton(TDMCreateLocale::FIELDS_LIST, 'fields.php', 'application-view-detail');
        $adminMenu->renderButton();		
		
		$obj = $fieldsHandler->create();
		$form = $xoops->getModuleForm($obj, 'fields');
        $xoops->tpl()->assign('form', $form->render());
    break;	
	
	case 'save':
		if (!$xoops->security()->check()) {
			$xoops->redirect('fields.php', 3, implode(',', $xoops->security()->getErrors()));
		}		
        if ($fieldId) {
            $obj = $fieldsHandler->get($fieldId);
        } else {
            $obj = $fieldsHandler->create();
        }			
		//Form fields
		$obj->setVars(array('field_mid' => $fieldMid, 'field_tid' => $fieldTid, 'field_name' => $fieldName, 
		                    'field_numb' => $fieldNumb, 'field_type' => $_POST['field_type'], 
							'field_value' => $_POST['field_value'], 'field_attribute' => $_POST['field_attribute'], 
							'field_null' => $_POST['field_null'], 'field_default' => $_POST['field_default'], 
							'field_key' => $_POST['field_key'],	'field_elements' => $_POST['field_elements'],
	                        'field_auto_increment' => (($_REQUEST['field_auto_increment'] == 1) ? '1' : '0'),
							'field_admin' => (($_REQUEST['field_admin'] == 1) ? '1' : '0'),
							'field_user' => (($_REQUEST['field_user'] == 1) ? '1' : '0'), 
							'field_blocks' => (($_REQUEST['field_blocks'] == 1) ? '1' : '0'), 
							'field_mainfield' => (($_REQUEST['field_mainfield'] == 1) ? '1' : '0'), 
							'field_search' =>  (($_REQUEST['field_search'] == 1) ? '1' : '0'), 
							'field_required' => (($_REQUEST['field_required'] == 1) ? '1' : '0')));	
		// Save data					
        if ($fieldsHandler->insert($obj)) {
            $xoops->redirect('fields.php', 2, TDMCreateLocale::FORMOK);
        }
		
        $xoops->error($obj->getHtmlErrors());
	break;		
	
	case 'edit':      
        $adminMenu->addItemButton(TDMCreateLocale::ADD_TABLE, 'tables.php?op=new', 'add');	
		$adminMenu->addItemButton(TDMCreateLocale::FIELDS_LIST, 'fields.php', 'application-view-detail');
        $adminMenu->renderButton();
		
		$obj = $fieldsHandler->get($fieldTid);		
		$form = $xoops->getModuleForm($obj, 'fields');
        $xoops->tpl()->assign('form', $form->render());
	break;	
		
	case 'delete':			
        if ($fieldId > 0) {
            $obj = $fieldsHandler->get($fieldId);			
			if (isset($_POST['ok']) && $_POST['ok'] == 1) {
                if (!$xoops->security()->check()) {
                    $xoops->redirect('fields.php', 3, implode(',', $xoops->security()->getErrors()));
                }
                if ($fieldsHandler->delete($obj)) {
                    $xoops->redirect('fields.php', 2, sprintf(TDMCreateLocale::S_DELETED, TDMCreateLocale::TABLE));
                } else {
                    $xoops->error($obj->getHtmlErrors());
                }
            } else {			
				$xoops->confirm(array('ok' => 1, 'id' => $fieldId, 'op' => 'delete'), 'fields.php', sprintf(TDMCreateLocale::QF_ARE_YOU_SURE_TO_DELETE, $obj->getVar('field_name')) . '<br />');
			}
		} else {
		    $xoops->redirect('fields.php', 1, TDMCreateLocale::E_DATABASE_ERROR);
		}
	break; 
}
$xoops->footer();