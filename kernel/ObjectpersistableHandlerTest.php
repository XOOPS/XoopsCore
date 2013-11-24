<?php
require_once(dirname(__FILE__).'/../init.php');

class ObjectpersistableHandler_XoopsObject extends XoopsObject
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ObjectpersistableHandler extends MY_UnitTestCase
{
    public $myclass = 'XoopsGroupHandler';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->sethandler();
        $this->assertSame(null,$value);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value=$instance->loadhandler('read');
        $this->assertTrue(is_object($value));
    }

    public function test_160() {
        $instance=new $this->myclass(null, '', 'XoopsBlock');
        $value=$instance->create();
        $this->assertInstanceOf('XoopsBlock',$value);
    }
    
    public function test_180() {
        $instance=new $this->myclass(null, '', 'XoopsBlock');
        $value=$instance->get();
        $this->assertInstanceOf('XoopsBlock',$value);
    }
    
    public function test_200() {
        $instance=new $this->myclass();
		$obj=new ObjectpersistableHandler_XoopsObject();
		$obj->setDirty();
		$instance->className=get_class($obj);
        $value=$instance->insert($obj);
        $this->assertSame(null,$value);
    }
    
    public function test_220() {
        $instance=new $this->myclass();
		$obj=new ObjectpersistableHandler_XoopsObject();
		$instance->className=get_class($obj);
        $value=$instance->delete($obj);
        $this->assertSame(false,$value);
    }
    
    public function test_240() {
        $instance=new $this->myclass();
        $value=$instance->deleteAll();
        $this->assertSame(false,$value);
    }
    
    public function test_260() {
        $instance=new $this->myclass();
        $value=$instance->updateAll('name','value');
        $this->assertSame(false,$value);
    }
    
    public function test_280() {
        $instance=new $this->myclass();
        $value=$instance->getObjects();
        $this->assertSame(array(),$value);
    }
    
    public function test_300() {
        $instance=new $this->myclass();
        $value=$instance->getAll();
        $this->assertSame(array(),$value);
    }
    
    public function test_320() {
        $instance=new $this->myclass();
        $value=$instance->getList();
        $this->assertSame(array(),$value);
    }
    
    public function test_340() {
        $instance=new $this->myclass();
        $value=$instance->getIds();
        $this->assertSame(array(),$value);
    }
    
    public function test_360() {
        $instance=new $this->myclass();
        $value=$instance->getCount();
        $this->assertSame(0,$value);
    }
    
    public function test_380() {
        $instance=new $this->myclass();
        $value=$instance->getCounts();
        $this->assertSame(array(),$value);
    }
    
    public function test_400() {
        $instance=new $this->myclass();
		$instance->table_link='table';
		$instance->field_link='field';
        $value=$instance->getByLink();
        $this->assertSame(array(),$value);
    }
    
    public function test_420() {
        $instance=new $this->myclass();
		$instance->table_link='table';
		$instance->field_link='field';
        $value=$instance->getCountByLink();
        $this->assertSame(false,$value);
    }
    
    public function test_440() {
        $instance=new $this->myclass();
		$instance->table_link='table';
		$instance->field_link='field';
        $value=$instance->getCountsByLink();
        $this->assertSame(false,$value);
    }
    
    public function test_460() {
        $instance=new $this->myclass();
		$instance->table_link='table';
		$instance->field_link='field';
        $value=$instance->updateByLink(array('key'=>'value'));
        $this->assertSame(false,$value);
    }
    
    public function test_470() {
        $instance=new $this->myclass();
		$instance->table_link='table';
		$instance->field_link='field';
        $value=$instance->deleteByLink();
        $this->assertSame(false,$value);
    }
    
    public function test_480() {
        $instance=new $this->myclass();
        $value=$instance->cleanOrphan('table','field','object');
        $this->assertSame(false,$value);
    }
    
    public function test_500() {
        $instance=new $this->myclass();
        $value=$instance->sinchronization();
        $this->assertSame(null,$value);
    }

}