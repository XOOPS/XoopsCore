<?php
require_once(__DIR__.'/../../../init_new.php');

class AuthAbstractTestInstance extends Xoops\Auth\AuthAbstract
{
    public function authenticate($uname, $pwd = null)
    {
        return false;
    }
}

class AuthAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'AuthAbstractTestInstance';

    public function test___construct()
    {
        $conn = \Xoops\Core\Database\Factory::getConnection();

        $instance = new $this->myclass($conn);
        $this->assertInstanceOf($this->myclass, $instance);
    }

    public function test_setErrors()
    {
        $dao = 'dao';
        $instance = new $this->myclass($dao);
        $errno = 1;
        $error = 'error';
        $instance->setErrors($errno, $error);
        $x = $instance->getErrors();
        $this->assertTrue(is_array($x));
        $this->assertTrue($x[$errno]==$error);
    }

    public function test_getHtmlErrors()
    {
        $dao = 'dao';
        $instance = new $this->myclass($dao);
        $errno = 1;
        $error = 'error';
        $instance->setErrors($errno, $error);
        $x = $instance->getHtmlErrors();
        $this->assertTrue(is_string($x));
    }
}
