<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

$xoops_root_path = \XoopsBaseConfig::get('root-path');
require_once($xoops_root_path.'/class/xml/rpc/xmlrpcparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcArrayTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsXmlRpcArray';
    
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceof($this->myclass, $instance);
		$this->assertInstanceof('XoopsXmlRpcTag', $instance);
	}

    public function test_add()
    {
        // see test_render
    }

    public function test_render()
    {
		$instance = new $this->myclass();
        
        $value = $instance->render();
        $this->assertSame('<value><array><data></data></array></value>', $value);
        
        $instance->add(clone($instance));
        $value = $instance->render();
        $this->assertSame('<value><array><data><value><array><data></data></array></value></data></array></value>', $value);
    }
}
