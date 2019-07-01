<?php
require_once(__DIR__ . '/../../init_new.php');

class ThemeSetAuthorHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'ThemeSetAuthorHandler';
    protected $object = null;

    protected function setUp()
    {
        $input = 'input';
        $this->object = new $this->myclass($input);
    }

    public function test___construct()
    {
        $instance = $this->object;
        $this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_getName()
    {
        $instance = $this->object;

        $name = $instance->getName();
        $this->assertSame('author', $name);
    }

    public function test_handleBeginElement()
    {
        $instance = $this->object;

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $params = [];
        $x = $instance->handleBeginElement($parser, $params);
        $this->assertSame([], $parser->tempArr);
    }

    public function test_handleEndElement()
    {
        $instance = $this->object;

        $this->markTestIncomplete();
    }
}
