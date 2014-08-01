<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcRequestTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcRequest';
    
    public function test___construct()
	{
		$value = 'value';
		$instance = new $this->myclass($value);
		$this->assertInstanceof($this->myclass, $instance);
		$this->assertInstanceof('XoopsXmlRpcDocument', $instance);
        
        $this->assertSame($value, $instance->methodName); 
	}

    public function test_render()
    {
		$instance = new $this->myclass();
        
        $x = $instance->render();
        $this->assertTrue(is_string($x));
        $this->assertTrue(!empty($x));
    }
}
