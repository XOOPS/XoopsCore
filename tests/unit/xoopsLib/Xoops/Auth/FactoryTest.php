<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Auth_FactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops\Auth\Factory';

    public function test___construct()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
    }
	
	public function test_getAuthConnection()
	{
		$class = $this->myClass;
		
		$xoops = \Xoops::getInstance();
		
		$uname = '';
		$instance = $class::getAuthConnection($uname);
		$this->assertInstanceOf('Xoops\Auth\Xoops', $instance);
	}
	
    /**
     *
     */
	public function test_getAuthConnection100()
	{
		$class = $this->myClass;
		if (!extension_loaded('ldap')) $this->markTestSkipped();
		
		$xoops = \Xoops::getInstance();
		$xoops->setConfig('auth_method', 'ads');
		
		$uname = '';
		$instance = $class::getAuthConnection($uname, true);
		$this->assertInstanceOf('Xoops\Auth\Ads', $instance);

	}
	
	public function test_getAuthConnection150()
	{
		$class = $this->myClass;
		if (!extension_loaded('ldap')) $this->markTestSkipped();
        
		$xoops = \Xoops::getInstance();
		$xoops->setConfig('auth_method', 'ads');
		
		$uname = 'admin';
		$instance = $class::getAuthConnection($uname, true);
		$this->assertInstanceOf('Xoops\Auth\Xoops', $instance);
	}
	
    /**
     * 
     */
	public function test_getAuthConnection200()
	{
		$class = $this->myClass;
		if (!extension_loaded('ldap')) $this->markTestSkipped();
		
		$xoops = \Xoops::getInstance();
		
		$xoops->setConfig('auth_method', 'ldap');
		
		$uname = '';
		$instance = $class::getAuthConnection($uname, true);
		$this->assertInstanceOf('Xoops\Auth\Ldap', $instance);
		
	}
	
	public function test_getAuthConnection250()
	{
		$class = $this->myClass;
		if (!extension_loaded('ldap')) $this->markTestSkipped();
        
		$xoops = \Xoops::getInstance();
		
		$xoops->setConfig('auth_method', 'ldap');
		
		$uname = 'admin';
		$instance = $class::getAuthConnection($uname, true);
		$this->assertInstanceOf('Xoops\Auth\Xoops', $instance);
		
	}

}
