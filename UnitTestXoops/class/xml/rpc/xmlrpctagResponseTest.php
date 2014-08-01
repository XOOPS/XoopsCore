<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcResponseTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcResponse';
    
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceof($this->myclass, $instance);
		$this->assertInstanceof('XoopsXmlRpcDocument', $instance);
	}

    public function test_render()
    {
		$instance = new $this->myclass();
        
        $x = $instance->render();
        $this->assertTrue(is_string($x));
        $this->assertTrue(!empty($x));
    }
}
