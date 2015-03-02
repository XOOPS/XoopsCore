<?php
require_once(dirname(__FILE__).'/../../../../init_mini.php');

class Xoops_Module_Plugin_AbstractTestInstance extends Xoops\Module\Plugin\PluginAbstract
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Module_Plugin_AbstractTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'Xoops_Module_Plugin_AbstractTestInstance';

    public function test___construct()
	{
		$dir = XOOPS_ROOT_PATH.'/modules/avatar';
		$instance = new $this->myclass($dir);
		$this->assertInstanceOf($this->myclass, $instance);
    }

}
