<?php
require_once(dirname(__FILE__).'/../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigCollectorTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'Xoops\Module\Plugin\ConfigCollector';

    public function test___construct()
	{
        $module = new XoopsModule();
        $configs = array();
        
		$instance = new $this->myclass($module, $configs);
		$this->assertInstanceOf($this->myclass, $instance);
    }
    
    public function test_add()
	{
        $module = new XoopsModule();
        $configs = array();
        
		$instance = new $this->myclass($module, $configs);
        
        $new = array('key1'=>'value1', 'key2'=>'value2', 'key3'=>'value3');
        $instance->add($new);
        
        $value = $instance->configs;
		$this->assertTrue(count($value)==count($new));
    }
    
    public function test_module()
	{
        $module = new XoopsModule();
        $configs = array();
        
		$instance = new $this->myclass($module, $configs);
        $value=$instance->module();

		$this->assertTrue($value==$module);
    }

}
