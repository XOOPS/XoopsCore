<?php   
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
use Xoops\Core\Kernel\Criteria;
/**
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.6.0
 * @author          TDM Xoops (AKA Developers)
 * @version         $Id: fields.php 11387 2013-04-16 15:19:57Z txmodxoops $
 */	
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class TDMCreateFieldsForm extends Xoops\Form\ThemeForm
{ 
	/**
     * @param TDMCreateFields|XoopsObject $obj
     */
	public function __construct(TDMCreateFields &$obj, $field_mid = 0, $field_tid = 0, $field_numb = 0, $field_name = '')
	{
	    $system = System::getInstance();
		$xoops = Xoops::getInstance();	
		$request = $xoops->request();
       	    
		$title = $obj->isNew() ? sprintf(TDMCreateLocale::ADD_FIELDS) : sprintf(TDMCreateLocale::EDIT_FIELDS);
        
		parent::__construct($title, 'form', false, 'post', true);
        $this->setExtra('enctype="multipart/form-data"');				
					
        $tables_Handler = $xoops->getModuleHandler('tables');					
		$criteria = new CriteriaCompo(new Criteria('table_mid', $field_mid));
		$criteria->add(new Criteria('table_id', $field_tid));					
		$criteria->setSort('table_name');
		$criteria->setOrder('ASC');
		$tables_arr = $tables_Handler->getAll($criteria);		
		unset($criteria);		
        
       	if (!$obj->isNew()) {
			$this->addElement(new Xoops\Form\Hidden('field_mid', $obj->getVar('field_mid')));	
		} else {
			$this->addElement(new Xoops\Form\Hidden('field_mid', $field_mid));		
		}	
		
		if (!$obj->isNew()) {
			$this->addElement(new Xoops\Form\Hidden('field_tid', $obj->getVar('field_tid')));	
		} else {
			$this->addElement(new Xoops\Form\Hidden('field_tid', $field_tid));		
		}

        if (!$obj->isNew()) {
			$numb_fields = $obj->getVar('field_numb');	
		} else {
			$numb_fields = $field_numb;		
		}	
		
		if ($numb_fields > 0) 
		{
			$main_tray = new Xoops\Form\ElementTray('&nbsp;');			
		
			$main_tray->addElement(new Xoops\Form\Raw('<table width=\'100%\' cellspacing=\'1\' class=\'outer\'>'));
			$main_tray->addElement(new Xoops\Form\Raw('<tr><th>'.XoopsLocale::ID.'</th>'));
			$main_tray->addElement(new Xoops\Form\Raw('<th>'.TDMCreateLocale::L_NAME.'</th>'));
			$main_tray->addElement(new Xoops\Form\Raw('<th>'.TDMCreateLocale::L_TYPE.'</th>'));
			$main_tray->addElement(new Xoops\Form\Raw('<th>'.TDMCreateLocale::L_VALUE.'</th>'));
			$main_tray->addElement(new Xoops\Form\Raw('<th>'.TDMCreateLocale::L_ATTRIBUTES.'</th>'));
			$main_tray->addElement(new Xoops\Form\Raw('<th>'.TDMCreateLocale::L_NOTNULL.'</th>'));
			$main_tray->addElement(new Xoops\Form\Raw('<th>'.TDMCreateLocale::L_DEFAULT.'</th>'));
			$main_tray->addElement(new Xoops\Form\Raw('<th>'.TDMCreateLocale::L_KEY.'</th>'));
			$main_tray->addElement(new Xoops\Form\Raw('<th>'.TDMCreateLocale::L_ARGUMENTS.'</th></tr>'));		
		    
			$fieldtype_Handler = $xoops->getModuleHandler('fieldtype');
			$fieldattrs_Handler = $xoops->getModuleHandler('fieldattributes');
			$fieldnull_Handler = $xoops->getModuleHandler('fieldnull');
			$fieldkey_Handler = $xoops->getModuleHandler('fieldkey');
			$fieldelements_Handler = $xoops->getModuleHandler('fieldelements');
			
			for($i = 0; $i < $numb_fields; $i++) 
			{    
			    $id = $i + 1;            		    
                $main_tray->addElement(new Xoops\Form\Raw('<tr class=\'top\'>'));
                $main_tray->addElement(new Xoops\Form\Raw('<td>'.$id.'</td>'));
                $field_value = ( $i == 1 ) ? '8' : $obj->getVar('field_value');		
                $fieldname = $obj->isNew() ? $field_name : $obj->getVar('field_name');				
				$field_name = new Xoops\Form\Text(TDMCreateLocale::FIELD_NAME, 'field_name', 1, 25, $field_name);
				$main_tray->addElement(new Xoops\Form\Raw('<td>'.$field_name->render().'</td>'));
			    $field_type_select = new Xoops\Form\Select(TDMCreateLocale::FIELD_TYPE, 'field_type', $obj->getVar('field_type'));      
				$field_type_select->addOptionArray($fieldtype_Handler->getList());
				$main_tray->addElement(new Xoops\Form\Raw('<td>'.$field_type_select->render().'</td>'));                
				$field_value = new Xoops\Form\Text(TDMCreateLocale::FIELD_VALUE, 'field_value', 1, 10, $field_value);
				$main_tray->addElement(new Xoops\Form\Raw('<td>'.$field_value->render().'</td>'));	
			    $field_attrs_select = new Xoops\Form\Select(TDMCreateLocale::FIELD_ATTRIBUTE, 'field_attribute', $obj->getVar('field_attribute'));                 				
				$field_attrs_select->addOptionArray($fieldattrs_Handler->getList());
				$main_tray->addElement(new Xoops\Form\Raw('<td>'.$field_attrs_select->render().'</td>'));				
			    $field_null_select = new Xoops\Form\Select(TDMCreateLocale::FIELD_NULL, 'field_null', $obj->getVar('field_null'));
				$field_null_select->addOptionArray($fieldnull_Handler->getList());
				$main_tray->addElement(new Xoops\Form\Raw('<td>'.$field_null_select->render().'</td>'));
				$field_default = new Xoops\Form\Text(TDMCreateLocale::FIELD_DEFAULT, 'field_default', 1, 25, $obj->getVar('field_default'));
				$main_tray->addElement(new Xoops\Form\Raw('<td>'.$field_default->render().'</td>'));
				$field_key_select = new Xoops\Form\Select(TDMCreateLocale::FIELD_KEY, 'field_key', $obj->getVar('field_key'));
				$field_key_select->addOptionArray($fieldkey_Handler->getList());
				$main_tray->addElement(new Xoops\Form\Raw('<td>'.$field_key_select->render().'</td>'));
                if($i == 0){				
					$field_autoincrement = $obj->getVar('field_auto_increment') ? $obj->getVar('field_auto_increment') : 0;
					$check_field_autoincrement = new Xoops\Form\CheckBox(' ', 'field_auto_increment', $field_autoincrement);
					$check_field_autoincrement->addOption(1, TDMCreateLocale::FIELD_AUTO_INCREMENT);
					$main_tray->addElement(new Xoops\Form\Raw('<td class=\'txtleft\'>'.$check_field_autoincrement->render().'</td>'));	
				} else {
				    $main_tray->addElement(new Xoops\Form\Raw('<td class=\'txtleft\'>'));
					$field_elements_select = new Xoops\Form\Select(TDMCreateLocale::FIELD_ELEMENTS, 'field_elements', $obj->getVar('field_elements'));	
					$field_elements_select->addOptionArray($fieldelements_Handler->getList());	
					foreach (array_keys($tables_arr) as $j) 
					{                                  
						$table_name = $tables_arr[$j]->getVar('table_name');
						if ( $j[$i] == 'Xoops\Form\Tables-'.$table_name ) {								
							$field_elements_select->addOption('Xoops\Form\Tables-'.$table_name, 'Table : '.$table_name);						
						}													
					}
					$mini_tray = new Xoops\Form\ElementTray('', '<br />');	
					//$mini_tray->addElement(new Xoops\Form\Raw('<table><thead><th>'.TDMCreateLocale::L_EXTRA.'</th></thead>'));
					$mini_tray->addElement(new Xoops\Form\Raw('<table>'));
					$mini_tray->addElement(new Xoops\Form\Raw('<tr><td class=\'txtleft\'>'.$field_elements_select->render().'</td></tr>'));
					$field_admin = $obj->getVar('field_admin') ? 0 : $obj->getVar('field_admin');
					$check_field_admin = new Xoops\Form\CheckBox(' ', 'field_admin', $field_admin);
					$check_field_admin->addOption(1, TDMCreateLocale::C_FIELD_ADMIN);
					$mini_tray->addElement(new Xoops\Form\Raw('<tr><td class=\'txtleft\'>'.$check_field_admin->render().'</td></tr>'));
					$field_user = $obj->getVar('field_user') ? 0 : $obj->getVar('field_user');
					$check_field_user = new Xoops\Form\CheckBox(' ', 'field_user', $field_user);
					$check_field_user->addOption(1, TDMCreateLocale::C_FIELD_USER);
					$mini_tray->addElement(new Xoops\Form\Raw('<tr><td class=\'txtleft\'>'.$check_field_user->render().'</td></tr>'));
					$field_block = $obj->getVar('field_blocks') ? 0 : $obj->getVar('field_blocks');
					$check_field_block = new Xoops\Form\CheckBox(' ', 'field_block', $field_block);
					$check_field_block->addOption(1, TDMCreateLocale::C_FIELD_BLOCK);
					$mini_tray->addElement(new Xoops\Form\Raw('<tr><td class=\'txtleft\'>'.$check_field_block->render().'</td></tr>'));
					$field_main = $obj->getVar('field_main') ? 0 : $obj->getVar('field_main');
					$field_main_radio = new Xoops\Form\Radio(' ', 'field_main', $field_main);
					$field_main_radio->addOption( '&nbsp;', TDMCreateLocale::C_FIELD_MAINFIELD );
					$mini_tray->addElement(new Xoops\Form\Raw('<tr><td class=\'txtleft\'>'.$field_main_radio->render().'</td></tr>')); 
					$field_search = $obj->getVar('field_search') ? 0 : $obj->getVar('field_search');
					$check_field_search = new Xoops\Form\CheckBox(' ', 'field_search', $field_search);
					$check_field_search->addOption(1, TDMCreateLocale::C_FIELD_SEARCH);				
					$mini_tray->addElement(new Xoops\Form\Raw('<tr><td class=\'txtleft\'>'.$check_field_search->render().'</td></tr>'));
					$field_required = $obj->getVar('field_required') ? 0 : $obj->getVar('field_required');
					$check_field_required = new Xoops\Form\CheckBox(' ', 'field_required', $field_required);
					$check_field_required->addOption(1, TDMCreateLocale::C_FIELD_REQUIRED);
					$mini_tray->addElement(new Xoops\Form\Raw('<tr><td class=\'txtleft\'>'.$check_field_required->render().'</td></tr>'));
					$mini_tray->addElement(new Xoops\Form\Raw('</table>'));					
					$main_tray->addElement($mini_tray);	 
                    $main_tray->addElement(new Xoops\Form\Raw('</td>'));					
				}/**/
				$main_tray->addElement(new Xoops\Form\Raw('</tr>'));				
            }  	
			//$main_tray->addElement(new Xoops\Form\Raw('</tbody>'));	      		
			unset($id);
			
			if (!$obj->isNew()) {
				$ftid = new Xoops\Form\Hidden('field_tid', $obj->getVar('field_tid'));
			} else {
			    $ftid = new Xoops\Form\Hidden('field_tid', $field_tid); 
			}
			
			$main_tray->addElement(new Xoops\Form\Raw('<tr><td colspan="7">'.$ftid->render().'</td>'));
			$main_tray->addElement(new Xoops\Form\Hidden('op', 'save'));
			$submit = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
			$submit->setClass('right');
			$main_tray->addElement(new Xoops\Form\Raw('<td colspan="2">'.$submit->render().'</td></tr></table>'));
			$this->addElement($main_tray);
		}  
	}
}