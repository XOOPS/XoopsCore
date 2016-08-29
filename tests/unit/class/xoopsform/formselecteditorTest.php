<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormSelectEditorTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsFormSelectEditor';

    public function test___construct()
	{
        $form = new \Xoops\Form\SimpleForm('title', 'name', 'action');
		$instance = new $this->myClass($form);
        $this->assertInstanceOf('Xoops\\Form\\SelectEditor', $instance);
    }

}
