<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Auth_AdsTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = '\Xoops\Auth\Ads';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        if (!extension_loaded('ldap')) $this->markTestSkipped();
    }

    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
    }

    public function test_authenticate()
    {
        $this->markTestIncomplete();
    }

    public function test_getUPN()
    {
        $this->markTestIncomplete();
    }
}
