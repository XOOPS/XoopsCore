<?php
require_once(__DIR__.'/../../../init_new.php');

class FormTextAreaTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'FormTextArea';

    public function test___construct()
    {
        $class = $this->myclass;
        $instance = new $class();
        $this->assertInstanceOf($class, $instance);
        $this->assertInstanceOf('XoopsEditor', $instance);
    }
}
