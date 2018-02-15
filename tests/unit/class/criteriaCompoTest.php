<?php
require_once(__DIR__.'/../init_new.php');

class CriteriaCompoTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'CriteriaCompo';
    
    public function test___construct()
    {
        $x = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $x);
        $this->assertInstanceOf('\\Xoops\\Core\\Kernel\\CriteriaCompo', $x);
    }
}
