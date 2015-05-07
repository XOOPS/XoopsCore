<?php
require_once(dirname(__FILE__).'/../../../../init_new.php');

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
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
		$dir = $xoops_root_path.'/modules/avatar';
		$instance = new $this->myclass($dir);
		$this->assertInstanceOf($this->myclass, $instance);
    }

}
