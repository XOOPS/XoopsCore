<?php
require_once(dirname(__FILE__).'/../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Kernel_CriteriaCompoTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Kernel\CriteriaCompo';
    
    public function test___construct()
	{
        $criteria = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $criteria);
        $this->assertInstanceOf('Xoops\Core\Kernel\CriteriaElement', $criteria);	
    }
    
    public function test_add()
	{
		$this->markTestIncomplete();
    }
	
    public function test_render()
	{
		$this->markTestIncomplete();
    }
	
    public function test_renderWhere()
	{
		$this->markTestIncomplete();
    }
	
    public function test_renderLdap()
	{
		$this->markTestIncomplete();
    }
	
    public function test_renderQb()
	{
		$this->markTestIncomplete();
    }
	
    public function test_buildExpressionQb()
	{
		$this->markTestIncomplete();
    }
	
}
