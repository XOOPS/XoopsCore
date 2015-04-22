<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SecurityTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops\Core\Security';

    public function test___construct()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
    }

    public function test_check()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        if(isset($_SESSION)) unset($_SESSION['XOOPS_TOKEN_SESSION']);
        $token = $instance->createToken();
        $this->assertTrue(!empty($token));

        $value = $instance->check(true, $token);
        $this->assertTrue($value);
        $this->assertTrue(empty($_SESSION['XOOPS_TOKEN_SESSION']));

        $value = $instance->check(true, false);
        $this->assertFalse($value);
        $err = $instance->getErrors();
        $this->assertTrue(is_array($err));

        $value = $instance->check(true, $token);
        $this->assertFalse($value);
        $err = $instance->getErrors();
        $this->assertTrue(is_array($err));

        unset($_SESSION['XOOPS_TOKEN_SESSION']);
        $token = $instance->createToken(1);
        $this->assertTrue(!empty($token));
        sleep(2);
        $value = $instance->check(true, $token);
        $this->assertFalse($value);
        $err = $instance->getErrors();
        $this->assertTrue(is_array($err));
        $this->assertTrue(empty($_SESSION['XOOPS_TOKEN_SESSION']));
    }

    public function test_createToken()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        $value = $instance->createToken();
        $x = $_SESSION['XOOPS_TOKEN_SESSION'];
        $token = array_pop($x);
        $this->assertFalse(is_null($token));
        $id = $token['id'];
        $expire = $token['expire'];
        $this->assertSame($id, $value);
        unset($_SESSION['XOOPS_TOKEN_SESSION']);

        $tkName = 'MY_TOKEN';
        $value = $instance->createToken(1, $tkName);
        $x = $_SESSION[$tkName.'_SESSION'];
        $token = array_pop($x);
        $this->assertFalse(is_null($token));
        $id = $token['id'];
        $this->assertSame($id, $value);
        unset($_SESSION['MY_TOKEN_SESSION']);
    }

    public function test_validateToken()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        unset($_SESSION['XOOPS_TOKEN_SESSION']);
        $token = $instance->createToken();
        $this->assertTrue(!empty($token));

        $value = $instance->validateToken($token);
        $this->assertTrue($value);
        $this->assertTrue(empty($_SESSION['XOOPS_TOKEN_SESSION']));

        $value = $instance->validateToken(false);
        $this->assertFalse($value);
        $err = $instance->getErrors();
        $this->assertTrue(is_array($err));

        $value = $instance->validateToken($token);
        $this->assertFalse($value);
        $err = $instance->getErrors();
        $this->assertTrue(is_array($err));

        unset($_SESSION['XOOPS_TOKEN_SESSION']);
        $token = $instance->createToken(1);
        $this->assertTrue(!empty($token));
        sleep(2);
        $value = $instance->validateToken($token);
        $this->assertFalse($value);
        $err = $instance->getErrors();
        $this->assertTrue(is_array($err));
        $this->assertTrue(empty($_SESSION['XOOPS_TOKEN_SESSION']));
    }

    public function test_clearTokens()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        unset($_SESSION['XOOPS_TOKEN_SESSION']);
        $token = $instance->createToken();
        $this->assertTrue(!empty($token));

        $instance->clearTokens();
        $this->assertTrue(empty($_SESSION['XOOPS_TOKEN_SESSION']));
    }

    public function test_filterToken()
    {
        // within test_garbageCollection
    }

    public function test_garbageCollection()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        unset($_SESSION['XOOPS_TOKEN_SESSION']);
        $token1 = $instance->createToken(1);
        $this->assertTrue(!empty($token1));

        $token2 = $instance->createToken(10);
        $this->assertTrue(!empty($token2));

        $this->assertTrue(count($_SESSION['XOOPS_TOKEN_SESSION']) == 2);

        sleep(2);

        $instance->garbageCollection();
        $this->assertTrue(count($_SESSION['XOOPS_TOKEN_SESSION']) == 1);
    }

    public function test_checkReferer()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        $value = $instance->checkReferer(0);
        $this->assertTrue($value);

        $refSave = $_SERVER['HTTP_REFERER'];

        $_SERVER['HTTP_REFERER'] = '';
        $value = $instance->checkReferer();
        $this->assertFalse($value);

        $_SERVER['HTTP_REFERER'] = 'dummy';
        $value = $instance->checkReferer();
        $this->assertFalse($value);

        $_SERVER['HTTP_REFERER'] = XOOPS_URL;
        $value = $instance->checkReferer();
        $this->assertTrue($value);

        $_SERVER['HTTP_REFERER'] = $refSave;

    }

    public function test_checkBadips()
    {
        $this->markTestIncomplete();
    }

    public function test_getTokenHTML()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        $value = $instance->getTokenHTML();
        $this->assertTrue(strpos($value, '<input type="hidden"') === 0);
        $this->assertTrue(strpos($value, 'name="XOOPS_TOKEN_REQUEST"') !== 0);
        $this->assertTrue(strpos($value, 'id="XOOPS_TOKEN_REQUEST"') !== 0);
        $this->assertTrue(strpos($value, 'value="') !== 0);

        $token = "MY_TOKEN";
        $value = $instance->getTokenHTML($token);
        $this->assertTrue(strpos($value, '<input type="hidden"') === 0);
        $this->assertTrue(strpos($value, 'name="'.$token.'_REQUEST"') !== 0);
        $this->assertTrue(strpos($value, 'id="'.$token.'_REQUEST"') !== 0);
        $this->assertTrue(strpos($value, 'value="') !== 0);
    }

    public function test_setErrors()
    {
        // see test_getErrors
    }

    public function test_getErrors()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        $str1 = "string1";
        $instance->setErrors($str1);
        $instance->setErrors($str1);
        $value = $instance->getErrors();
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)==2);
        $value = $instance->getErrors(true);
        $this->assertTrue(is_string($value));
        $this->assertSame($str1.'<br />'.$str1.'<br />', $value);
    }

}
