<?php
require_once(__DIR__.'/../../../../init_new.php');

class Xoops_Locale_Mailer_AbstractTestInstance extends Xoops_Locale_Mailer_Abstract
{
}

class Xoops_Locale_Mailer_AbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'Xoops_Locale_Mailer_AbstractTestInstance';
    
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
	public function test_encodeFromName()
	{
		$instance = new $this->myclass();
		$text = 'foo';
		$value = $instance->encodeFromName($text);
		$this->assertSame($text, $value);
	}
	
	public function test_encodeSubject()
	{
		$instance = new $this->myclass();
		$text = 'foo';
		$value = $instance->encodeSubject($text);
		$this->assertSame($text, $value);
	}

}
