<?php
require_once(dirname(__FILE__).'/../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsMailerTest extends MY_UnitTestCase
{
	protected $myclass = 'XoopsMailer';

    public function test___construct()
	{
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
    
    public function test_setHTML()
    {
        $this->markTestincomplete();
    }

    public function test_reset()
    {
        $this->markTestincomplete();
    }
    
    public function test_setTemplateDir()
    {
        $this->markTestincomplete();
    }
    
    public function test_getTemplatePath()
    {
        $this->markTestincomplete();
    }
    
    public function test_setTemplate()
    {
        $this->markTestincomplete();
    }
    
    public function test_setFromEmail()
    {
        $this->markTestincomplete();
    }
    
    public function test_setFromName()
    {
        $this->markTestincomplete();
    }
    
    public function test_setFromUser()
    {
        $this->markTestincomplete();
    }
    
    public function test_setPriority()
    {
        $this->markTestincomplete();
    }
    
    public function test_setSubject()
    {
        $this->markTestincomplete();
    }
    
    public function test_setBody()
    {
        $this->markTestincomplete();
    }
  
    public function test_useMail()
    {
        $this->markTestincomplete();
    }
    
    public function test_usePM()
    {
        $this->markTestincomplete();
    }
    
    public function test_send()
    {
        $this->markTestincomplete();
    }
    
    public function test_getErrors()
    {
        $this->markTestincomplete();
    }
    
    public function test_getSuccess()
    {
        $this->markTestincomplete();
    }
    
    public function test_assign()
    {
        $this->markTestincomplete();
    }
    
    public function test_addHeaders()
    {
        $this->markTestincomplete();
    }
    
    public function test_setToEmails()
    {
        $this->markTestincomplete();
    }
    
    public function test_setToUsers()
    {
        $this->markTestincomplete();
    }
    
    public function test_setToGroups()
    {
        $this->markTestincomplete();
    }
    
    public function test_encodeFromName()
    {
        $this->markTestincomplete();
    }
    
    public function test_encodeSubject()
    {
        $this->markTestincomplete();
    }
    
    public function test_encodeBody()
    {
        $this->markTestincomplete();
    }
}
