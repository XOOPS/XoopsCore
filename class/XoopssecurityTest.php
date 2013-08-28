<?php
require_once(dirname(__FILE__).'/../init.php');
 
class XoopssecurityTest extends MY_UnitTestCase
{
	protected $myclass = 'XoopsSecurity';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
    public function test_120() {
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$token=$instance->createToken();
		$this->assertTrue(is_string($token));
		$check=$instance->check(true,$token);
		$this->assertSame(true, $check);
		foreach($_SESSION['XOOPS_TOKEN_SESSION'] as $k => $v) {
			$this->assertTrue(is_string($v['id']) AND (strlen($v['id'])==32));
			$this->assertTrue(is_int($v['expire']) AND ($v['expire'] > time()));
		}
    }
	
	// this test clear all token in global $_SESSION
    public function test_140() {
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$token=$instance->clearTokens();
		$this->assertTrue(empty($_SESSION['XOOPS_TOKEN_SESSION']));
    }
	
    public function test_160() {
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$token=$instance->createToken();
		$this->assertTrue(is_string($token));
		$check=$instance->check(true,$token);
		$this->assertSame(true, $check);
        $token_data['expire'] = time() + 10;
		$expire=$instance->filterToken($token_data);
		$this->assertSame(true, $expire);
    }
	
    public function test_180() {
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$_SESSION['XOOPS_TOKEN_SESSION'][] = array('expire' => time() + 10);
		$_SESSION['XOOPS_TOKEN_SESSION'][] = array('expire' => time() + 11);
		$_SESSION['XOOPS_TOKEN_SESSION'][] = array('expire' => time() + 12);
		$value=$instance->garbageCollection();
		$this->assertTrue(!empty($_SESSION['XOOPS_TOKEN_SESSION']));
    }
	
    public function test_200() {
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$value=$instance->checkReferer(0);
		$this->assertSame(true, $value);
		$value=$instance->checkReferer();
		$this->assertSame(false, $value);
    }
	
	
    public function test_300() {
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$value=$instance->getTokenHTML();
		$this->assertTrue(is_string($value));
		$this->assertTrue(strpos($value,'input type="hidden"')>0);
		$this->assertTrue(strpos($value,'name="XOOPS_TOKEN_REQUEST"')>0);
		$this->assertTrue(strpos($value,'id="XOOPS_TOKEN_REQUEST')>0);
    }
	
    public function test_320() {
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$error="   error   ";
		$instance->setErrors($error);
		$errors=$instance->getErrors();
		$this->assertSame(trim($error), $errors[0]);
    }
}
