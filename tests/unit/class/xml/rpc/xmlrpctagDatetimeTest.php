<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcDatetimeTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsXmlRpcDatetime';
    
    public function test___construct()
	{
		$value = 1000;
		$x = new $this->myclass($value);
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcTag', $x);
	}

    public function test_render()
    {
		$value = 1000;
		$instance = new $this->myclass($value);
        
        $result = $instance->render();
        $this->assertSame('<value><dateTime.iso8601>' . gmstrftime("%Y%m%dT%H:%M:%S", $value) . '</dateTime.iso8601></value>', $result);
        
		$value = 'now';
		$instance = new $this->myclass($value);
        
        $result = $instance->render();
        $this->assertSame('<value><dateTime.iso8601>' . gmstrftime("%Y%m%dT%H:%M:%S", strtotime($value)) . '</dateTime.iso8601></value>', $result);

    }
}
