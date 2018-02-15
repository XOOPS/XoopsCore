<?php
require_once(__DIR__.'/../../../init_new.php');

$xoops_root_path = \XoopsBaseConfig::get('root-path');
require_once($xoops_root_path.'/class/xml/rpc/xmlrpcparser.php');

class XoopsXmlRpcTagTestInstance extends XoopsXmlRpcTag
{
    public function render()
    {
    }
}

class XoopsXmlRpcTagTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsXmlRpcTagTestInstance';

    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceof($this->myclass, $instance);
    }

    public function test_encode()
    {
        $this->markTestSkipped('needs updated');
        $instance = new $this->myclass();
        $text = '& < > ';
        $result = $instance->encode($text);
        $expected = '&amp; &lt; &gt; ';
        $this->assertSame($expected, $result);

        $text = '#||amp||#';
        $result = $instance->encode($text);
        $expected = '&amp;';
        $this->assertSame($expected, $result);

        $this->markTestIncomplete('Unexpected result in the next test');
        $text = '&amp;';
        $result = $instance->encode($text);
        $expected = '#||amp||#';
        $this->assertSame($expected, $result);
    }

    public function test_setFault()
    {
        $instance = new $this->myclass();

        $instance->setFault(true);
        $x = $instance->isFault();
        $this->assertSame(true, $x);

        $instance->setFault(false);
        $x = $instance->isFault();
        $this->assertSame(false, $x);
    }
}
