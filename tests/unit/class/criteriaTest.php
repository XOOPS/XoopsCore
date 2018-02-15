<?php
require_once(__DIR__.'/../init_new.php');

class CriteriaTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'Criteria';
    
    public function test___construct()
    {
        $column = 'column';
        $x = new $this->myclass($column);
        $this->assertInstanceOf($this->myclass, $x);
        $this->assertInstanceOf('\\Xoops\\Core\\Kernel\\Criteria', $x);
    }
}
