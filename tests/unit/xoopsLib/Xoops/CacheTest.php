<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_CacheTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops_Cache';
    
    public function setUp()
	{
    }
	
	public function test___construct()
	{
        $instance = new $this->myClass;
        $this->assertInstanceOf('\\Xoops\\Core\\Cache\\Legacy', $instance);
	}
}
