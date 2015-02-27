<?php
require_once(dirname(__FILE__).'/../../../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsDebugStackTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops\Core\Database\Logging\XoopsDebugStack';
	
    public function test___construct()
	{
		$instance = new $this->myClass();
		$this->assertInstanceOf($this->myClass, $instance);
		$this->assertInstanceOf('Doctrine\DBAL\Logging\DebugStack', $instance);
    }
	
    public function test_stopQuery()
	{
		$instance = new $this->myClass();
		$this->assertInstanceOf($this->myClass, $instance);
		
		$instance->stopQuery();
    }

}
