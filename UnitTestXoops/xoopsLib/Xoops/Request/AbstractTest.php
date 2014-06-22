<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

class Xoops_Request_AbstractTestInstance extends Xoops_Request_Abstract
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
    protected $myclass = 'Xoops_Request_AbstractTestInstance';
    
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
		$params = array('p1'=>'one');
		$instance = new $this->myclass($params);
		$name = 'p1';
		$x = $instance->asArray($name);
		$this->assertTrue(is_array($x));
		$this->assertTrue($x == array('one'));
		
		$name = 'not_used';
		$x = $instance->asArray($name);
		$this->assertTrue(is_array($x));
		$this->assertTrue(empty($x));
		
		$name = 'p1';
		$x = $instance->asArray($name, array(), array(array('one')));
		$this->assertTrue(is_array($x));
		$this->assertTrue($x == array('one'));
		
		$name = 'p1';
		$x = $instance->asArray($name, array('result'), array(array('one')), array(array('one')));
		$this->assertTrue(is_array($x));
		$this->assertTrue($x == array('result'));
		
    }

    public function test_asStr()
	{
		$params = array('p1'=>'one');
		$instance = new $this->myclass($params);
		
		$name = 'p1';
		$x = $instance->asStr($name);
		$this->assertTrue(is_string($x));
		$this->assertTrue($x == 'one');
		
		$name = 'not_used';
		$x = $instance->asStr($name);
		$this->assertTrue(is_string($x));
		$this->assertTrue(empty($x));
		
		$name = 'p1';
		$x = $instance->asStr($name, '', array('one'));
		$this->assertTrue(is_string($x));
		$this->assertTrue($x == 'one');
		
		$name = 'p1';
		$x = $instance->asStr($name, 'default', array('one'), array('one'));
		$this->assertTrue(is_string($x));
		$this->assertTrue($x == 'default');
		
    }
	
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function test_asStr100()
	{
		$params = array('p1'=>array('test1','test2'));
		$instance = new $this->myclass($params);
		
		$name = 'p1';
		$x = $instance->asStr($name);
		$this->assertFalse($x);
		
    }
	
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function test_asStr200()
	{
		$params = array('p1'=>new StdClass());
		$instance = new $this->myclass($params);
		
		$name = 'p1';
		$x = $instance->asStr($name);
		$this->assertFalse($x);
		
    }
	
    public function test_asInt()
	{
		$params = array('p1'=>'71');
		$instance = new $this->myclass($params);
		
		$name = 'p1';
		$x = $instance->asInt($name);
		$this->assertTrue(is_int($x));
		$this->assertTrue($x == 71);
		
		$name = 'not_used';
		$x = $instance->asInt($name);
		$this->assertTrue(is_int($x));
		$this->assertTrue(empty($x));
		
		$name = 'p1';
		$x = $instance->asInt($name, '', array(71));
		$this->assertTrue(is_int($x));
		$this->assertTrue($x == 71);
		
		$name = 'p1';
		$x = $instance->asInt($name, 17, array(71), array(71));
		$this->assertTrue(is_int($x));
		$this->assertTrue($x == 17);
		
    }
	
    public function test_asInt100()
	{
		$params = array('p1'=>array('test1','test2'));
		$instance = new $this->myclass($params);
		
		$name = 'p1';
		$x = $instance->asInt($name);
		$this->assertTrue($x == 1);
		
    }
	
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function test_asInt200()
	{
		$params = array('p1'=>new StdClass());
		$instance = new $this->myclass($params);
		
		$name = 'p1';
		$x = $instance->asInt($name);
		$this->assertFalse($x);
		
    }
	
    public function test_asBool()
	{
		$params = array('p1'=>'1');
		$instance = new $this->myclass($params);
		
		$name = 'p1';
		$x = $instance->asBool($name);
		$this->assertTrue(is_bool($x));
		$this->assertTrue($x);
		
		$name = 'not_used';
		$x = $instance->asBool($name);
		$this->assertTrue(is_bool($x));
		$this->assertTrue(!$x);
		
		$name = 'p1';
		$x = $instance->asBool($name, '', array(true));
		$this->assertTrue(is_bool($x));
		$this->assertTrue($x);
		
		$name = 'p1';
		$x = $instance->asBool($name, false, array(true), array(true));
		$this->assertTrue(is_bool($x));
		$this->assertTrue(!$x);
    }
	
    public function test_asFloat()
	{
		$params = array('p1'=>'7.1');
		$instance = new $this->myclass($params);
		
		$name = 'p1';
		$x = $instance->asFloat($name);
		$this->assertTrue(is_float($x));
		$this->assertTrue($x == 7.1);
		
		$name = 'not_used';
		$x = $instance->asFloat($name);
		$this->assertTrue(is_float($x));
		$this->assertTrue($x == 0.0);
		
		$name = 'p1';
		$x = $instance->asFloat($name, 0.0, array(true));
		$this->assertTrue(is_float($x));
		$this->assertTrue($x == 7.1);
		
		$name = 'p1';
		$x = $instance->asFloat($name, 1.7, array(true), array(true));
		$this->assertTrue(is_float($x));
		$this->assertTrue($x == 1.7);
    }
	
    public function test_hasParam()
	{
		$params = array('p1'=>'7.1');
		$instance = new $this->myclass($params);
		
		$value = $instance->hasParam('p1');
		$this->assertTrue($value);
    }
	
    public function test_getParam()
	{
		$params = array('p1'=>'one', 'p2'=>'two');
		$instance = new $this->myclass($params);
		
		$value = $instance->getParam('p1');
		$this->assertSame('one', $value);
		
		$value = $instance->getParam();
		$this->assertSame($params, $value);
		
		$default = 'default';
		$value = $instance->getParam('no_key', $default);
		$this->assertSame($default, $value);
    }
	
    public function test_setParam()
	{
		$params = array('p1'=>'one');
		$instance = new $this->myclass($params);
		
		$instance->setParam('p2', 'two');
		
		$value = $instance->getParam('p1');
		$this->assertSame('one', $value);
		
		$value = $instance->getParam('p2');
		$this->assertSame('two', $value);
    }
	
    public function test_addParams()
	{
		$params = array('p1'=>'one');
		$instance = new $this->myclass($params);
		
		$instance->addParams(array('p2' => 'two'));
		
		$value = $instance->getParam('p1');
		$this->assertSame('one', $value);
		
		$value = $instance->getParam('p2');
		$this->assertSame('two', $value);
    }
}
