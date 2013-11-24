<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TreeTest extends MY_UnitTestCase
{
    
    public function SetUp() {
    }
    
    public function test_100() {
        $myId = 'Id';
        $parentId = 'parentId';
        $rootId = 'rootId';
        $item1 = new XoopsConfigItem();
        $item1->initVar('Id',XOBJ_DTYPE_INT,71);
        $item1->initVar('parentId',XOBJ_DTYPE_INT);
        $item1->initVar('rootId',XOBJ_DTYPE_INT);

        $item2 = new XoopsConfigItem();
        $item2->initVar('Id',XOBJ_DTYPE_INT,72);
        $item2->initVar('parentId',XOBJ_DTYPE_INT,71);
        $item2->initVar('rootId',XOBJ_DTYPE_INT);

        $item3 = new XoopsConfigItem();
        $item3->initVar('Id',XOBJ_DTYPE_INT,73);
        $item3->initVar('parentId',XOBJ_DTYPE_INT,72);
        $item3->initVar('rootId',XOBJ_DTYPE_INT);
        $objectArr = array($item1,$item2,$item3);
        
        $instance=new XoopsObjectTree($objectArr, $myId, $parentId);
        $this->assertInstanceOf('XoopsObjectTree', $instance);

        $tree=$instance->getTree();
        $this->assertTrue(is_array($tree));

        $ret=$instance->getByKey(72);
        $this->assertEquals(72, $ret->getVar('Id'));        
    }
}
