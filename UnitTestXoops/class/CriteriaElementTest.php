<?php
require_once(dirname(__FILE__).'/../init.php');

class CriteriaElementInstance extends CriteriaElement
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class CriteriaElementTest extends MY_UnitTestCase
{
    protected $myclass = 'CriteriaElementInstance';
    
    public function test___construct()
	{
		$x = new $this->myclass();
        $this->assertInstanceOf('CriteriaElement', $x);
        $this->assertInstanceOf('Xoops\Core\Kernel\CriteriaElement', $x);
    }
        
}
