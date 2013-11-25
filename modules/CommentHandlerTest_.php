<?php
require_once(dirname(__FILE__).'/../init.php');

class CommentHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsCommentHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$this->assertPattern('/^.*xoopscomments$/',$instance->table);
		$this->assertIdentical($instance->className,'XoopsComment');
		$this->assertIdentical($instance->keyName,'com_id');
		$this->assertIdentical($instance->identifierName,'com_title');
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $module=1;
        $item=1;
        $value=$instance->getByItemId($module,$item);
        $this->assertIsA($instance,$this->myclass);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $module=1;
        $item=1;
        $value=$instance->getCountByItemId($module,$item);
        $this->assertIdentical($value,0);
    }
    
    public function test_160() {
        $instance=new $this->myclass();
        $module=1;
        $value=$instance->getCountByModuleId($module);
        $this->assertIdentical($value,0);
    }
    
    public function test_180() {
        $instance=new $this->myclass();
        $module=1;
        $item=1;
        $order='asc';
        $value=$instance->getTopComments($module,$item,$order);
        $this->assertIsA($value,'array');
    }
    
    public function test_200() {
        $instance=new $this->myclass();
        $comment_root=1;
        $comment=1;
        $value=$instance->getThread($comment_root,$comment);
        $this->assertIsA($value,'array');
    }
    
    public function test_220() {
        $instance=new $this->myclass();
        $comment=new XoopsComment();
        $comment->setDirty();
        $xname='name';
        $xvalue='value';
        $value=$instance->updateByField($comment,$xname,$xvalue);
        $this->assertIdentical($value,'');
    }
    
    public function test_240() {
        $instance=new $this->myclass();
        $module=1;
        $value=$instance->deleteByModule($module);
        $this->assertIdentical($value,false);
    }
    
    public function test_260() {
        $instance=new $this->myclass();
        $module=1;
        $item=1;
        $value=$instance->deleteByItemId($module,$item);
        $this->assertIdentical($value,true);
    }
    
}