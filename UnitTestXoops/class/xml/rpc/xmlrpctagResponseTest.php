<?php
require_once(__DIR__.'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcResponseTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcResponse';
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
	}

    public function test_render()
    {
		$instance = $this->object;
        
        $x = $instance->render();
        $this->assertTrue(is_string($x));
        $this->assertTrue(!empty($x));
    }
}
