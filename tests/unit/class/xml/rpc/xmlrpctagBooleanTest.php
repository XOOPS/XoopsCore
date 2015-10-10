<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcBooleanTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsXmlRpcBoolean';
    
    public function test___construct()
	{
		$value = 1;
		$x = new $this->myclass($value);
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcTag', $x);
	}

    public function test_render()
    {
		$value = 1;
		$instance = new $this->myclass($value);
        
        $value = $instance->render();
        $this->assertSame('<value><boolean>1</boolean></value>', $value);
        
		$instance = new $this->myclass();
        
        $value = $instance->render();
        $this->assertSame('<value><boolean>0</boolean></value>', $value);
    }
}
