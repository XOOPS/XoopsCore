<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class FormTextAreaTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'FormTextArea';

    public function test___construct()
    {
		$class = $this->myclass;
		$instance = new $class();
		$this->assertInstanceOf($class, $instance);
		$this->assertInstanceOf('XoopsEditor', $instance);
	}
}
