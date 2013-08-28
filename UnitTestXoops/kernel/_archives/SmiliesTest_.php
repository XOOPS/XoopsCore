<?php
require_once('../init.php');
//require_once(XOOPS_ROOT_PATH.'/kernel/smilies.php');

class TestOfXoopsSmilies extends UnitTestCase
{
    var $myclass='XoopsSmilies';

    public function SetUp() {
    }
	
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['id']));
        $this->assertTrue(isset($value['code']));
        $this->assertTrue(isset($value['smile_url']));
        $this->assertTrue(isset($value['emotion']));
        $this->assertTrue(isset($value['display']));
    }
}

class TestOfXoopsSmiliesHandler extends UnitTestCase
{
    var $myclass='XoopsSmiliesHandler';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$this->assertPattern('/^.*smiles$/',$instance->table);
		$this->assertIdentical($instance->className,'XoopsSmilies');
		$this->assertIdentical($instance->keyName,'id');
		$this->assertIdentical($instance->identifierName,'code');
    }

}