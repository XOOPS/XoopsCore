<?php
require_once(dirname(__FILE__).'/../../../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsDebugStackTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Database\Logging\XoopsDebugStack';
	
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Doctrine\DBAL\Logging\DebugStack', $instance);
    }
	
    public function test_stopQuery()
	{
		$this->markTestIncomplete();
    }

}
