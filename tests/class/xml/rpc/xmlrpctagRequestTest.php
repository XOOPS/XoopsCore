<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcRequestTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsXmlRpcRequest';
    protected $object = null;
    
    public function setUp()
    {
		$input = 'input';
		$this->object = new $this->myclass($input);
    }
    
    public function test___construct()
	{
        $instance = $this->object;
		$this->assertInstanceof('XoopsXmlRpcDocument', $instance);
        
        $this->assertSame('input', $instance->methodName); 
	}

    public function test_render()
    {
        $instance = $this->object;
        
        $x = $instance->render();
        $this->assertTrue(is_string($x));
        $this->assertTrue(!empty($x));
    }
}
