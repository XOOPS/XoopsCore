<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class UserTest extends \PHPUnit_Framework_TestCase
{
    var $myclass='XoopsUser';

    public function setUp()
	{
    }

    public function test___construct()
	{
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
    
    public function test_isGuest()
	{
        $instance=new $this->myclass();
        $value=$instance->isGuest();
        $this->assertSame(false,$value);
    }

    public function test_getUnameFromId()
	{
		$class = $this->myclass;
        $value=$class::getUnameFromId(1);
        $this->assertSame('admin',$value);
    }

    public function test_incrementPost()
	{
        $instance=new $this->myclass();
        $value=$instance->incrementPost();
        $this->assertSame('',$value);
    }

    public function test_getGroups()
	{
        $instance=new $this->myclass();
        $group=$instance->getGroups();
        $value=$instance->setGroups($group);
        $this->assertSame(null,$value);
    }
	
    public function test_setGroups()
	{
    }

    public function test_groups()
	{
        $instance=new $this->myclass();
        $group1=$instance->getGroups();
        $group2=$instance->groups();
        $this->assertSame($group1,$group2);
    }

    public function test_isAdmin()
	{
        $instance=new $this->myclass();
        $value=$instance->isAdmin();
        $this->assertSame(false,$value);
    }

    public function test_rank()
	{
        $instance=new $this->myclass();
        $value=$instance->rank();
        $this->assertSame(null,$value);
    }

    public function test_isActive()
	{
        $instance=new $this->myclass();
        $value=$instance->isActive();
        $this->assertSame(false,$value);
    }

    public function test_isOnline()
	{
        $instance=new $this->myclass();
        $value=$instance->isOnline();
		$this->markTestSkipped('');
        $this->assertSame(false,$value);
    }

    public function test_uid()
	{
        $instance=new $this->myclass();
        $value=$instance->uid();
        $this->assertSame(null,$value);
    }

    public function test_id()
	{
        $instance=new $this->myclass();
        $value=$instance->id();
        $this->assertSame($instance->uid(),$value);
    }

    public function test_name()
	{
        $instance=new $this->myclass();
        $value=$instance->name();
        $this->assertSame(null,$value);
    }

    public function test_email()
	{
        $instance=new $this->myclass();
        $value=$instance->email();
        $this->assertSame(null,$value);
    }

    public function test_url()
	{
        $instance=new $this->myclass();
        $value=$instance->url();
        $this->assertSame(null,$value);
    }

    public function test_user_avatar()
	{
        $instance=new $this->myclass();
        $value=$instance->user_avatar();
        $this->assertSame(null,$value);
    }

    public function test_user_regdate()
	{
        $instance=new $this->myclass();
        $value=$instance->user_regdate();
        $this->assertSame(null,$value);
    }

    public function test_user_icq()
	{
        $instance=new $this->myclass();
        $value=$instance->user_icq();
        $this->assertSame('',$value);
    }

    public function test_user_from()
	{
        $instance=new $this->myclass();
        $value=$instance->user_from();
        $this->assertSame(null,$value);
    }

    public function test_user_sig()
	{
        $instance=new $this->myclass();
        $value=$instance->user_sig();
        $this->assertSame(null,$value);
    }

    public function test_user_viewemail()
	{
        $instance=new $this->myclass();
        $value=$instance->user_viewemail();
        $this->assertSame(0,$value);
    }

    public function test_actkey()
	{
        $instance=new $this->myclass();
        $value=$instance->actkey();
        $this->assertSame(null,$value);
    }

    public function test_user_aim()
	{
        $instance=new $this->myclass();
        $value=$instance->user_aim();
        $this->assertSame(null,$value);
    }

    public function test_user_yim()
	{
        $instance=new $this->myclass();
        $value=$instance->user_yim();
        $this->assertSame(null,$value);
    }

    public function test_user_msnm()
	{
        $instance=new $this->myclass();
        $value=$instance->user_msnm();
        $this->assertSame(null,$value);
    }

    public function test_pass()
	{
        $instance=new $this->myclass();
        $value=$instance->pass();
        $this->assertSame(null,$value);
    }

    public function test_posts()
	{
        $instance=new $this->myclass();
        $value=$instance->posts();
        $this->assertSame(null,$value);
    }

    public function test_attachsig()
	{
        $instance=new $this->myclass();
        $value=$instance->attachsig();
        $this->assertSame(0,$value);
    }

    public function test_level()
	{
        $instance=new $this->myclass();
        $value=$instance->level();
        $this->assertSame(0,$value);
    }

    public function test_theme()
	{
        $instance=new $this->myclass();
        $value=$instance->theme();
        $this->assertSame(null,$value);
    }

    public function test_timezone()
	{
        $instance=new $this->myclass();
        $value=$instance->timezone();
        $this->assertSame('0.0',$value);
    }

    public function test_umode()
	{
        $instance=new $this->myclass();
        $value=$instance->umode();
        $this->assertSame(null,$value);
    }

    public function test_uorder()
	{
        $instance=new $this->myclass();
        $value=$instance->uorder();
        $this->assertSame(1,$value);
    }

    public function test_notify_method()
	{
        $instance=new $this->myclass();
        $value=$instance->notify_method();
        $this->assertSame(1,$value);
    }

    public function test_notify_mode()
	{
        $instance=new $this->myclass();
        $value=$instance->notify_mode();
        $this->assertSame(0,$value);
    }

    public function test_user_occ()
	{
        $instance=new $this->myclass();
        $value=$instance->user_occ();
        $this->assertSame(null,$value);
    }

    public function test_bio()
	{
        $instance=new $this->myclass();
        $value=$instance->bio();
        $this->assertSame(null,$value);
    }

    public function test_user_intrest() {
        $instance=new $this->myclass();
        $value=$instance->user_intrest();
        $this->assertSame(null,$value);
    }

}
