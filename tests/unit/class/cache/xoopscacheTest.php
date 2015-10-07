<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsCacheTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsCache';

    public function test__construct()
	{
        if (!class_exists('XoopsCache', false)) {
            $xoops_root_path = \XoopsBaseConfig::get('root-path');
            require_once $xoops_root_path . '/class/cache/xoopscache.php';
        }
		$instance = new $this->myclass(null);
		$this->assertInstanceOf('\\Xoops\\Core\\Cache\\Legacy', $instance);
    }

}
