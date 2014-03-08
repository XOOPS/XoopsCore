<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class CriteriaCompoTest extends MY_UnitTestCase
{
    protected $myclass = 'CriteriaCompo';
    
    public function test___construct()
	{
		$x = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $x);
        $this->assertInstanceOf('Xoops\Core\Kernel\CriteriaCompo', $x);
    }
        
}
