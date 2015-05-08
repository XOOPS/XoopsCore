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
 * @version         $Id: imports.php 10665 2012-12-27 10:14:15Z timgno $
 */
include __DIR__ . '/header.php';
// heaser
$xoops->header('admin:tdmcreate/tdmcreate_imports.tpl');
//
//$importId = Request::getInt('import_id', 0);
//
$adminMenu->renderNavigation('imports.php');
//
switch ($op) 
{  	
	case 'list':     
        $adminMenu->addItemButton(TDMCreateLocale::IMPORT_OLD_MODULE, 'imports.php?op=new', 'add');            
		$adminMenu->renderButton();		
		// Get modules list
        $criteria = new CriteriaCompo();
        $criteria->setSort('import_id');
        $criteria->setOrder('ASC'); 
        $criteria->setStart($start);
        $criteria->setLimit($limit);		
        $numImports = $importHandler->getCount($criteria);
		$importArr = $importHandler->getAll($criteria);
        // Assign Template variables
        $xoops->tpl()->assign('imports_count', $numImports);		
		unset($criteria);          
        if ($numImports > 0) {
            foreach (array_keys($importArr) as $i) {
                $import['id'] = $importArr[$i]->getVar('import_id');                 
                $import['mid'] = $importArr[$i]->getVar('import_mid');
				$import['name'] = $importArr[$i]->getVar('import_name');
				$import['nbtables'] = $importArr[$i]->getVar('import_nbtables'); 
                $import['nbfields'] = $importArr[$i]->getVar('import_nbfields');                
				$xoops->tpl()->append_by_ref('imports', $import);
                unset($import);				
            }
            // Display Page Navigation
			if ($numrows > $limit) {
				$nav = new XoopsPageNav($numrows, $limit, $start, 'start');
				$xoops->tpl()->assign('pagenav', $nav->renderNav(4));
			}
        } else {
            $xoops->tpl()->assign('error_message', TDMCreateLocale::IMPORT_ERROR_NOIMPORTS);
        }	
    break;
    	 
	case 'new':
        $adminMenu->addItemButton(TDMCreateLocale::IMPORTED_LIST, 'imports.php', 'application-view-detail');
        $adminMenu->renderButton();

		$obj = $importHandler->create();
        $form = $xoops->getModuleForm($obj, 'imports');
        $xoops->tpl()->assign('form', $form->render());	
	break;
	
	case 'save':
        if (!$xoops->security()->check()) {
			$xoops->redirect('modules.php', 3, implode(',', $xoops->security()->getErrors()));
		}
		
        if ($importId > 0) {
            $obj = $importHandler->get($importId);
			//Form imported edited save		
			$obj->setVar('import_mid', $_POST['import_mid']);
			$obj->setVar('import_name', $_POST['import_name']);
			$obj->setVar('import_nbtables', $_POST['import_nbtables']); 	
			$obj->setVar('import_tablename', $_POST['import_mid']);
			$obj->setVar('import_nbfields', $_POST['import_nbfields']);
			$obj->setVar('import_fieldelements', $_POST['import_fieldelements']);			
        } else {
            $obj = $importHandler->create();
			//Form imported save			
			$obj->setVar('import_name', $_POST['import_name']);	
			$obj->setVar('import_mid', $_POST['import_mid']);
        	$files = $_FILES['importfile'];
			// If incoming data have been entered correctly
			if($_POST['upload'] == XoopsLocale::A_SUBMIT && isset($files['tmp_name']) && (substr($files['name'], -4) == '.sql'))
			{	
				// File recovery
				$dir = TDMC_UPLOAD_PATH_FILES; 
				$file = $_FILES['importfile'];
				$tmpName = $file['tmp_name'];
				// Copy files to the server
				if (is_uploaded_file($tmpName)) {				
					readfile($tmpName);
					// The directory where you saved the file
					if ($file['error'] == UPLOAD_ERR_OK) {					
						if (move_uploaded_file($tmpName, $dir.'/'.$file['name']));
						$xoops->redirect('imports.php', 3, sprintf(TDMCreateLocale::E_FILE_UPLOADING, $file['name']));
					}
				} else {
					$xoops->redirect('imports.php', 3, sprintf(TDMCreateLocale::E_FILE_NOT_UPLOADING, $tmpName));
				}           
					 
				// Copies data in the db         
				$filename = $dir.'/'.$file['name'];			
				// File size
				$filesize = $files['size'];			
				// Check that the file was inserted and that there is
				if ( ($handle = fopen($filename, 'r') ) !== false) {			    							
					// File reading until at the end				
					while ( !feof( $handle ))
					{ 	
						$buffer = fgets($handle, filesize($filename));			    				
						if(strlen($buffer) > 1)
						{ 						
							// search all comments
							$search = array ( '/\/\*.*(\n)*.*(\*\/)?/', '/\s*--.*\n/', '/\s*#.*\n/' );  
							// and replace with null
							$replace = array ( "\n" );
							$buffer = preg_replace($search, $replace, $buffer);							
							$buffer = str_replace('`', '', $buffer);
                            $buffer = str_replace(',', '', $buffer);							
							
							preg_match_all('/((\s)*(CREATE TABLE)(\s)+(.*)(\s)+(\())/', $buffer, $tableMatch); // table name ... (match)
							if(count($tableMatch[0]) > 0) {
								array_push( $resultTable, $tableMatch[5][0] );
							}
						}  else { 
							$xoops->redirect('imports.php', 3, sprintf(TDMCreateLocale::E_SQL_FILE_DATA_NOT_MATCH, $buffer));
						} 				 
					}	
					
					// Insert query 
					if(strlen($resultTable[0]) > 0) 
					{			
                        $t = 0;                         						
						foreach(array_keys($resultTable) as $table) 
						{	
						    $obj->setVar('import_tablename', $resultTable[$table]); //$_POST['import_tablename']	
							$obj->setVar('import_nbtables', $t); $t++;	//$_POST['import_nbtables']	
                            if(strlen($resultTable[0]) > 0) 
							{	
							    $f = 0;						
								foreach(array_keys($resultFields) as $field)
								{															
									$obj->setVar('import_nbfields', $f); $f++; // $_POST['import_nbfields']
									$obj->setVar('import_fieldelements', $_POST['import_fieldelements']);									
								}
								unset($f);	
                            }								
						}	
                        unset($t);						
					}				
				} else {				
					$xoops->redirect('imports.php', 3, TDMCreateLocale::E_FILE_NOT_OPEN_READING);
				}
				$xoops->redirect('imports.php', 3, TDMCreateLocale::S_DATA_ENTERED);
				fclose($handle);
			} else {
				$xoops->redirect('imports.php', 3, TDMCreateLocale::E_DATABASE_SQL_FILE_NOT_IMPORTED);
			}		
		}
		
		if ($importHandler->insert($obj)) {
            $xoops->redirect('imports.php', 3, TDMCreateLocale::FORM_OK);
        }		

        $xoops->error($obj->getHtmlErrors());
        $form = $xoops->getModuleForm($obj, 'import');
        $xoops->tpl()->assign('form', $form->render());
	break;
	
	case 'edit':
        $adminMenu->addItemButton(TDMCreateLocale::IMPORT_OLD_MODULE, 'imports.php?op=import', 'add');   
		$adminMenu->addItemButton(TDMCreateLocale::IMPORTED_LIST, 'imports.php', 'application-view-detail');
        $adminMenu->renderButton();		
		
		$obj = $importHandler->get($importId);
		$form = $xoops->getModuleForm($obj, 'import');
		$xoops->tpl()->assign('form', $form->render());
	break;	
	
	case 'delete': 
        if ($importId > 0) {
            $obj = $importHandler->get($importId);			
			if (isset($_POST['ok']) && $_POST['ok'] == 1) {
                if (!$xoops->security()->check()) {
                    $xoops->redirect('imports.php', 3, implode(',', $xoops->security()->getErrors()));
                }
                if ($importHandler->delete($obj)) {
                    $xoops->redirect('imports.php', 2, sprintf(TDMCreateLocale::S_DELETED, TDMCreateLocale::IMPORT));
                } else {
                    $xoops->error($obj->getHtmlErrors());
                }
            } else {			
				$xoops->confirm(array('ok' => 1, 'id' => $importId, 'op' => 'delete'), 'imports.php', sprintf(TDMCreateLocale::QF_ARE_YOU_SURE_TO_DELETE, $obj->getVar('import_name')) . '<br />');
			}
		} else {
		    $xoops->redirect('imports.php', 1, TDMCreateLocale::E_DATABASE_ERROR);
		}	
    break;
}

include __DIR__ . '/footer.php';