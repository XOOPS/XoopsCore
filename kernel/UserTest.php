<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class UserTest extends MY_UnitTestCase
{
    var $myclass='XoopsUser';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['uid']));
        $this->assertTrue(isset($value['name']));
        $this->assertTrue(isset($value['uname']));
        $this->assertTrue(isset($value['email']));
        $this->assertTrue(isset($value['url']));
        $this->assertTrue(isset($value['user_avatar']));
        $this->assertTrue(isset($value['user_regdate']));
        $this->assertTrue(isset($value['user_icq']));
        $this->assertTrue(isset($value['user_from']));
        $this->assertTrue(isset($value['user_sig']));
        $this->assertTrue(isset($value['user_viewemail']));
        $this->assertTrue(isset($value['actkey']));
        $this->assertTrue(isset($value['user_aim']));
        $this->assertTrue(isset($value['user_yim']));
        $this->assertTrue(isset($value['user_msnm']));
        $this->assertTrue(isset($value['pass']));
        $this->assertTrue(isset($value['posts']));
        $this->assertTrue(isset($value['attachsig']));
        $this->assertTrue(isset($value['rank']));
        $this->assertTrue(isset($value['level']));
        $this->assertTrue(isset($value['theme']));
        $this->assertTrue(isset($value['timezone_offset']));
        $this->assertTrue(isset($value['last_login']));
        $this->assertTrue(isset($value['umode']));
        $this->assertTrue(isset($value['uorder']));
        $this->assertTrue(isset($value['notify_method']));
        $this->assertTrue(isset($value['notify_mode']));
        $this->assertTrue(isset($value['user_occ']));
        $this->assertTrue(isset($value['bio']));
        $this->assertTrue(isset($value['user_intrest']));
        $this->assertTrue(isset($value['user_mailok']));
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->isGuest();
        $this->assertSame(false,$value);
    }

    public function test_140() {
        $instance=new $this->myclass();
        $value=XoopsUser::getUnameFromId(1);
        $this->assertSame('admin',$value);
    }

    public function test_160() {
        $instance=new $this->myclass();
        $value=$instance->incrementPost();
        $this->assertSame('',$value);
    }

    public function test_180() {
        $instance=new $this->myclass();
        $group=$instance->getGroups();
        $value=$instance->setGroups($group);
        $this->assertSame(null,$value);
    }

    public function test_200() {
        $instance=new $this->myclass();
        $group1=$instance->getGroups();
        $group2=$instance->groups();
        $this->assertSame($group1,$group2);
    }

    public function test_220() {
        $instance=new $this->myclass();
        $value=$instance->isAdmin();
        $this->assertSame(false,$value);
    }

    public function test_240() {
        $instance=new $this->myclass();
        $value=$instance->rank();
        $this->assertSame(null,$value);
    }

    public function test_260() {
        $instance=new $this->myclass();
        $value=$instance->isActive();
        $this->assertSame(false,$value);
    }

    public function test_280() {
        $instance=new $this->myclass();
        $value=$instance->isOnline();
		$this->markTestSkipped('');
        $this->assertSame(false,$value);
    }

    public function test_300() {
        $instance=new $this->myclass();
        $value=$instance->uid();
        $this->assertSame(null,$value);
    }

    public function test_320() {
        $instance=new $this->myclass();
        $value=$instance->id();
        $this->assertSame($instance->uid(),$value);
    }

    public function test_340() {
        $instance=new $this->myclass();
        $value=$instance->name();
        $this->assertSame(null,$value);
    }

    public function test_360() {
        $instance=new $this->myclass();
        $value=$instance->email();
        $this->assertSame(null,$value);
    }

    public function test_380() {
        $instance=new $this->myclass();
        $value=$instance->url();
        $this->assertSame(null,$value);
    }

    public function test_400() {
        $instance=new $this->myclass();
        $value=$instance->user_avatar();
        $this->assertSame(null,$value);
    }

    public function test_420() {
        $instance=new $this->myclass();
        $value=$instance->user_regdate();
        $this->assertSame(null,$value);
    }

    public function test_440() {
        $instance=new $this->myclass();
        $value=$instance->user_icq();
        $this->assertSame('',$value);
    }

    public function test_460() {
        $instance=new $this->myclass();
        $value=$instance->user_from();
        $this->assertSame(null,$value);
    }

    public function test_480() {
        $instance=new $this->myclass();
        $value=$instance->user_sig();
        $this->assertSame(null,$value);
    }

    public function test_500() {
        $instance=new $this->myclass();
        $value=$instance->user_viewemail();
        $this->assertSame(0,$value);
    }

    public function test_520() {
        $instance=new $this->myclass();
        $value=$instance->actkey();
        $this->assertSame(null,$value);
    }

    public function test_530() {
        $instance=new $this->myclass();
        $value=$instance->user_aim();
        $this->assertSame(null,$value);
    }

    public function test_540() {
        $instance=new $this->myclass();
        $value=$instance->user_yim();
        $this->assertSame(null,$value);
    }

    public function test_560() {
        $instance=new $this->myclass();
        $value=$instance->user_msnm();
        $this->assertSame(null,$value);
    }

    public function test_580() {
        $instance=new $this->myclass();
        $value=$instance->pass();
        $this->assertSame(null,$value);
    }

    public function test_600() {
        $instance=new $this->myclass();
        $value=$instance->posts();
        $this->assertSame(null,$value);
    }

    public function test_620() {
        $instance=new $this->myclass();
        $value=$instance->attachsig();
        $this->assertSame(0,$value);
    }

    public function test_640() {
        $instance=new $this->myclass();
        $value=$instance->level();
        $this->assertSame(0,$value);
    }

    public function test_660() {
        $instance=new $this->myclass();
        $value=$instance->theme();
        $this->assertSame(null,$value);
    }

    public function test_680() {
        $instance=new $this->myclass();
        $value=$instance->timezone();
        $this->assertSame('0.0',$value);
    }

    public function test_700() {
        $instance=new $this->myclass();
        $value=$instance->umode();
        $this->assertSame(null,$value);
    }

    public function test_720() {
        $instance=new $this->myclass();
        $value=$instance->uorder();
        $this->assertSame(1,$value);
    }

    public function test_740() {
        $instance=new $this->myclass();
        $value=$instance->notify_method();
        $this->assertSame(1,$value);
    }

    public function test_760() {
        $instance=new $this->myclass();
        $value=$instance->notify_mode();
        $this->assertSame(0,$value);
    }

    public function test_780() {
        $instance=new $this->myclass();
        $value=$instance->user_occ();
        $this->assertSame(null,$value);
    }

    public function test_800() {
        $instance=new $this->myclass();
        $value=$instance->bio();
        $this->assertSame(null,$value);
    }

    public function test_820() {
        $instance=new $this->myclass();
        $value=$instance->user_intrest();
        $this->assertSame(null,$value);
    }

}
