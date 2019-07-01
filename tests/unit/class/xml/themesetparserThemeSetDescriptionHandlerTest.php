<?php
require_once(__DIR__ . '/../../init_new.php');

class ThemeSetDescriptionHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'ThemeSetDescriptionHandler';
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
        $this->assertSame('description', $name);
    }

    public function test_handleCharacterData()
    {
        $instance = $this->object;

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = ['template', 'template'];
        $data = 'description';
        $x = $instance->handleCharacterData($parser, $data);
        $this->assertNull($x);
        $this->assertSame($data, $parser->getTempArr('description'));

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = ['image', 'image'];
        $data = 'description';
        $x = $instance->handleCharacterData($parser, $data);
        $this->assertNull($x);
        $this->assertSame($data, $parser->getTempArr('description'));

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = ['dummy', 'dummy'];
        $data = 'description';
        $x = $instance->handleCharacterData($parser, $data);
        $this->assertNull($x);
        $this->assertFalse($parser->getTempArr('description'));
    }
}
