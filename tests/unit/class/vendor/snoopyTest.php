<?php
require_once(__DIR__.'/../../init_new.php');

class SnoopyTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'Snoopy';

    public function test___construct()
	{
		$x = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $x);
    }

}
