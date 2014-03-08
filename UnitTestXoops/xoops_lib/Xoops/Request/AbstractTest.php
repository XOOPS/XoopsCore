<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

class Xoops_Request_AbstractInstance extends Xoops_Request_Abstract
{
	function __construct(array $params)
	{
		parent::__construct($params);
	}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Request_AbstractTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Request_AbstractInstance';
    
    public function test___construct()
	{
		$params = array();
		$instance = new $this->myclass($params);
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
    public function test_getParams()
	{
		$params = array('p1'=>'one');
		$instance = new $this->myclass($params);
		$x = $instance->getParams();
		$this->assertSame($params, $x);
    }
	
    public function test_asArray()
	{
		$this->markTestIncomplete();
    }

    public function test_asStr()
	{
		$this->markTestIncomplete();
    }
	
    public function test_asInt()
	{
		$this->markTestIncomplete();
    }
	
    public function test_asBool()
	{
		$this->markTestIncomplete();
    }
	
    public function test_asFloat()
	{
		$this->markTestIncomplete();
    }
	
    public function test_hasParam()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getParam()
	{
		$this->markTestIncomplete();
    }
	
    public function test_setParam()
	{
		$this->markTestIncomplete();
    }
	
    public function test_addParams()
	{
		$this->markTestIncomplete();
    }
}
