<?php
require_once(__DIR__ . '/../../init_new.php');

class XoopsThemeSetParserTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsThemeSetParser';
    protected $object = null;

    protected function setUp()
    {
        $input = 'input';
        $this->object = new $this->myclass($input);
    }

    public function test___publicProperties()
    {
        $items = ['tempArr', 'themeSetData', 'imagesData', 'templatesData'];
        foreach ($items as $item) {
            $prop = new ReflectionProperty($this->myclass, $item);
            $this->assertTrue($prop->isPublic());
        }
    }

    public function test___construct()
    {
        $instance = $this->object;
        $this->assertInstanceOf('SaxParser', $instance);

        $tagHandlers = $instance->tagHandlers;
        $this->assertInternalType('array', $tagHandlers);
        if (is_array($tagHandlers)) {
            $this->assertCount(12, $tagHandlers);
        }
    }

    public function test_getThemeSetData()
    {
        $instance = $this->object;

        $name = 'name';
        $value = 'value';
        $instance->setThemeSetData($name, $value);
        $x = $instance->getThemeSetData('bidon');
        $this->assertFalse($x);

        $x = $instance->getThemeSetData($name);
        $this->assertSame($value, $x);

        $x = $instance->getThemeSetData();
        $this->assertTrue(is_array($x) and ($x[$name] == $value));
    }

    public function test_getImagesData()
    {
        $instance = $this->object;

        $arr = [1 => '1', 2 => '2'];
        $instance->setImagesData($arr);
        $x = $instance->getImagesData();
        $this->assertTrue(is_array($x) and (1 == count($x)));

        $arr = [1 => '1', 2 => '2'];
        $instance->setImagesData($arr);
        $x = $instance->getImagesData();
        $this->assertTrue(is_array($x) and (2 == count($x)));
    }

    public function test_getTemplatesData()
    {
        $instance = $this->object;

        $arr = [1 => '1', 2 => '2'];
        $instance->setTemplatesData($arr);
        $x = $instance->getTemplatesData();
        $this->assertTrue(is_array($x) and (1 == count($x)));

        $arr = [1 => '1', 2 => '2'];
        $instance->setTemplatesData($arr);
        $x = $instance->getTemplatesData();
        $this->assertTrue(is_array($x) and (2 == count($x)));
    }

    public function test_getTempArr()
    {
        $instance = $this->object;

        $name = 'name';
        $value = 'value';
        $delim = ';';
        $instance->setTempArr($name, $value);
        $x = $instance->getTempArr($name);
        $this->assertSame($value, $x);

        $x = $instance->getTempArr('bidon');
        $this->assertFalse($x);

        $x = $instance->getTempArr();
        $this->assertTrue(is_array($x) and ($x[$name] == $value));

        $instance->setTempArr($name, $value, $delim);
        $x = $instance->getTempArr($name);
        $this->assertSame($value . $delim . $value, $x);
    }

    public function test_resetTempArr()
    {
        $instance = $this->object;

        $name = 'name';
        $value = 'value';
        $delim = ';';
        $instance->setTempArr($name, $value);
        $x = $instance->getTempArr($name);
        $this->assertSame($value, $x);

        $instance->resetTempArr();

        $x = $instance->getTempArr($name);
        $this->assertFalse($x);
    }
}
