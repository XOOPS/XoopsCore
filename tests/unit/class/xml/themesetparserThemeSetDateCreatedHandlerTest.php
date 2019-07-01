<?php
require_once(__DIR__ . '/../../init_new.php');

class ThemeSetDateCreatedHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'ThemeSetDateCreatedHandler';
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
        $this->assertSame('dateCreated', $name);
    }

    public function test_handleCharacterData()
    {
        $instance = $this->object;

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = ['themeset', 'themeset'];
        $data = 'data';
        $instance->handleCharacterData($parser, $data);
        $this->assertSame($data, $parser->getThemeSetData('date'));

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = ['dummy', 'dummy'];
        $data = 'data';
        $instance->handleCharacterData($parser, $data);
        $this->assertFalse($parser->getThemeSetData('date'));
    }
}
