<?php
require_once(__DIR__ . '/../../init_new.php');

class ThemeSetImageHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'ThemeSetImageHandler';
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
        $this->assertSame('image', $name);
    }

    public function test_handleBeginElement()
    {
        $instance = $this->object;

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $attributes = ['name' => 'name'];
        $instance->handleBeginElement($parser, $attributes);
        $this->assertSame('name', $parser->getTempArr('name'));
    }

    public function test_handleEndElement()
    {
        $instance = $this->object;

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $attributes = ['name' => 'name'];
        $instance->handleBeginElement($parser, $attributes);

        $instance->handleEndElement($parser);
        $x = $parser->getImagesData();
        $this->assertInternalType('array', $x);
        $this->assertSame('name', $x[0]['name']);
    }
}
