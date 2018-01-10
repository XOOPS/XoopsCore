<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class SnoopyTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'Snoopy';

    public function test___construct()
	{
		$x = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $x);
    }

}
