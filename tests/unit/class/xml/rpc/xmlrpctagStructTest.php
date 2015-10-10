<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcStructTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsXmlRpcStruct';
    
    public function test___construct()
	{
		$x = new $this->myclass();
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcTag', $x);
	}

    public function test_add()
    {
        // see test_render
    }

    public function test_render()
    {
		$instance = new $this->myclass();
        
        $value = $instance->render();
        $this->assertSame('<value><struct></struct></value>', $value);
        
        $instance->add('instance', clone($instance));
        $value = $instance->render();
        $expected = '<value><struct>'
            . '<member><name>instance</name>'
            . '<value><struct></struct></value>'
            . '</member></struct></value>';
        $this->assertSame($expected, $value);

    }
}
