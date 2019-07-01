<?php
require_once(__DIR__ . '/../../init_new.php');

class ThemeSetNameHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'ThemeSetNameHandler';
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
        $this->assertSame('name', $name);
    }

    public function test_handleCharacterData()
    {
        $instance = $this->object;

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = ['themeset', 'themeset'];
        $data = 'data';
        $x = $instance->handleCharacterData($parser, $data);
        $this->assertNull($x);
        $this->assertSame($data, $parser->getThemeSetData('name'));

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = ['author', 'author'];
        $data = 'data';
        $x = $instance->handleCharacterData($parser, $data);
        $this->assertNull($x);
        $this->assertSame($data, $parser->getTempArr('name'));

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = ['dummy', 'dummy'];
        $data = 'data';
        $x = $instance->handleCharacterData($parser, $data);
        $this->assertNull($x);
        $this->assertFalse($parser->getThemeSetData('name'));
        $this->assertFalse($parser->getTempArr('name'));
    }
}
