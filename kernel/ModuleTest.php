<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ModuleTest extends MY_UnitTestCase
{
    var $myclass='XoopsModule';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['mid']));
        $this->assertTrue(isset($value['name']));
        $this->assertTrue(isset($value['version']));
        $this->assertTrue(isset($value['last_update']));
        $this->assertTrue(isset($value['weight']));
        $this->assertTrue(isset($value['isactive']));
        $this->assertTrue(isset($value['dirname']));
        $this->assertTrue(isset($value['hasmain']));
        $this->assertTrue(isset($value['hasadmin']));
        $this->assertTrue(isset($value['hassearch']));
        $this->assertTrue(isset($value['hasconfig']));
        $this->assertTrue(isset($value['hascomments']));
        $this->assertTrue(isset($value['hasnotification']));
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->loadInfoAsVar('');
        $this->assertSame(null,$value);
    }

    public function test_140() {
        $instance=new $this->myclass();
        $msgs=array(' toto ','titi');
        foreach($msgs as $msg)
            $instance->setMessage($msg);
        $value=$instance->getMessages();
        $this->assertTrue(is_array($value));
    }

    public function test_160() {
        $instance=new $this->myclass();
        $name='name';
        $val='value';
        $instance->setInfo($name,$val);
        $value=$instance->getInfo($name);
        $this->assertSame($val,$value);
    }

    public function test_180() {
        $instance=new $this->myclass();
        $name='name';
        $val='value';
        $instance->setInfo($name,'');
        $value=$instance->getInfo($name);
        $this->assertSame('',$value);
    }

    public function test_200() {
        $instance=new $this->myclass();
        $value=$instance->getInfo();
        $this->assertSame(null,$value);
    }

    public function test_220() {
        $instance=new $this->myclass();
        $name='name';
        $value=$instance->getInfo($name);
        $this->assertSame(false,$value);
    }

    public function test_260() {
        $instance=new $this->myclass();
        $value=$instance->mainLink();
        $this->assertSame(false,$value);
    }

    public function test_280() {
        $instance=new $this->myclass();
        $instance->setVar('hasmain',1);
        $value=$instance->mainLink();
        $this->assertTrue(is_string($value));
    }

    public function test_300() {
        $instance=new $this->myclass();
        $value=$instance->subLink();
        $this->assertTrue(is_array($value));
    }

    public function test_320() { // test en reserve
        $this->assertTrue(true);
    }

    public function test_340() {
        $instance=new $this->myclass();
        $instance->loadAdminMenu();
        $value=$instance->getAdminMenu();
        $this->assertSame(null,$value);
    }

    public function test_360() {
        $instance=new $this->myclass();
        $value=$instance->loadInfo('avatars');
        $this->assertSame(true,$value);
    }

    public function test_380() {
        $instance=new $this->myclass();
        $value=$instance->search();
        $this->assertSame(false,$value);
    }

    public function test_400() {
        $instance=new $this->myclass();
        $value=$instance->id();
        $this->assertSame(null,$value);
    }

    public function test_420() {
        $instance=new $this->myclass();
        $value=$instance->mid();
        $this->assertSame(null,$value);
    }

    public function test_440() {
        $instance=new $this->myclass();
        $value=$instance->name();
        $this->assertSame(null,$value);
    }

    public function test_460() {
        $instance=new $this->myclass();
        $value=$instance->version();
        $this->assertSame(100,$value);
    }

    public function test_480() {
        $instance=new $this->myclass();
        $value=$instance->last_update();
        $this->assertSame(null,$value);
    }

    public function test_500() {
        $instance=new $this->myclass();
        $value=$instance->weight();
        $this->assertSame(0,$value);
    }

    public function test_520() {
        $instance=new $this->myclass();
        $value=$instance->isactive();
        $this->assertSame(1,$value);
    }

    public function test_540() {
        $instance=new $this->myclass();
        $value=$instance->dirname();
        $this->assertSame(null,$value);
    }

    public function test_560() {
        $instance=new $this->myclass();
        $value=$instance->hasmain();
        $this->assertSame(0,$value);
    }

    public function test_580() {
        $instance=new $this->myclass();
        $value=$instance->hasadmin();
        $this->assertSame(0,$value);
    }

    public function test_600() {
        $instance=new $this->myclass();
        $value=$instance->hassearch();
        $this->assertSame(0,$value);
    }

    public function test_620() {
        $instance=new $this->myclass();
        $value=$instance->hasconfig();
        $this->assertSame(0,$value);
    }

    public function test_640() {
        $instance=new $this->myclass();
        $value=$instance->hascomments();
        $this->assertSame(0,$value);
    }

    public function test_660() {
        $instance=new $this->myclass();
        $value=$instance->hasnotification();
        $this->assertSame(0,$value);
    }

    public function test_680() {
        $instance=new $this->myclass();
        $value=$instance->getByDirName('.');
        $this->assertSame(false,$value);
    }

}
