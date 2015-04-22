<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

class Xoops_Auth_ProvisioningTest_AuthAbstractInstance extends Xoops\Auth\AuthAbstract
{
    function authenticate($uname, $pwd = null) {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Auth_ProvisioningTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'Xoops\Auth\Provisioning';

    public function test___construct()
    {
        $conn = \Xoops\Core\Database\Factory::getConnection();
        $auth = new Xoops_Auth_ProvisioningTest_AuthAbstractInstance($conn);

        $instance = new $this->myclass($auth);
        $this->assertInstanceOf($this->myclass, $instance);
    }

    public function test_getInstance()
    {
        $conn = \Xoops\Core\Database\Factory::getConnection();
        $auth = new Xoops_Auth_ProvisioningTest_AuthAbstractInstance($conn);

        $class = $this->myclass;
        $instance = $class::getInstance($auth);
        $this->assertInstanceOf($this->myclass, $instance);

        $instance2 = $class::getInstance($auth);
        $this->assertSame($instance, $instance2);
    }

    public function test_getXoopsUser()
    {
        $conn = \Xoops\Core\Database\Factory::getConnection();
        $auth = new Xoops_Auth_ProvisioningTest_AuthAbstractInstance($conn);

        $class = $this->myclass;
        $instance = $class::getInstance($auth);

        $value = $instance->getXoopsUser('not_a_user');
        $this->assertFalse($value);

        $memberHandler = \Xoops::getInstance()->getHandlerMember();
        $userObject = $memberHandler->getUser(1);
        $userName = $userObject->getVar('uname');

        $value = $instance->getXoopsUser($userName);
        $this->assertTrue(is_a($value, 'XoopsUser'));
    }

    public function test_sync()
    {
        $conn = \Xoops\Core\Database\Factory::getConnection();
        $auth = new Xoops_Auth_ProvisioningTest_AuthAbstractInstance($conn);

        $class = $this->myclass;
        $instance = $class::getInstance($auth);

        $value = $instance->sync(array(), 'not_a_user');
        $this->assertFalse($value);

        $memberHandler = \Xoops::getInstance()->getHandlerMember();
        $userObject = $memberHandler->getUser(1);
        $userName = $userObject->getVar('uname');

        $value = $instance->sync(array(), $userName);
        $this->assertTrue(is_a($value, 'XoopsUser'));
    }

    public function test_setVarsMapping()
    {
        $this->markTestIncomplete();
    }

    public function test_add()
    {
        $this->markTestIncomplete();
    }

    public function test_change()
    {
        $this->markTestIncomplete();
    }

    public function test_delete()
    {
        $conn = \Xoops\Core\Database\Factory::getConnection();
        $auth = new Xoops_Auth_ProvisioningTest_AuthAbstractInstance($conn);

        $class = $this->myclass;
        $instance = $class::getInstance($auth);

        $instance->delete();
        $this->assertTrue(true); // always OK
    }

    public function test_suspend()
    {
        $conn = \Xoops\Core\Database\Factory::getConnection();
        $auth = new Xoops_Auth_ProvisioningTest_AuthAbstractInstance($conn);

        $class = $this->myclass;
        $instance = $class::getInstance($auth);

        $instance->suspend();
        $this->assertTrue(true); // always OK
    }

    public function test_restore()
    {
        $conn = \Xoops\Core\Database\Factory::getConnection();
        $auth = new Xoops_Auth_ProvisioningTest_AuthAbstractInstance($conn);

        $class = $this->myclass;
        $instance = $class::getInstance($auth);

        $instance->restore();
        $this->assertTrue(true); // always OK
    }

    public function test_resetpwd()
    {
        $conn = \Xoops\Core\Database\Factory::getConnection();
        $auth = new Xoops_Auth_ProvisioningTest_AuthAbstractInstance($conn);

        $class = $this->myclass;
        $instance = $class::getInstance($auth);

        $instance->resetpwd();
        $this->assertTrue(true); // always OK
    }
}
