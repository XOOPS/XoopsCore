<?php
require_once(dirname(__FILE__).'/../init.php');
 
class CommentrenderTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCommentRenderer';
    protected $tpl = null;
    
    public function SetUp() {
        $this->tpl = new XoopsTpl();
        $xoops = Xoops::getInstance();
        $xoops->loadLanguage('comment');
        //include_once $xoops->path('include/comment_constants.php');
    }
    
    public function test_100() {
        $renderer = new $this->myclass($this->tpl);
        $this->assertInstanceOf($this->myclass, $renderer);
    }
    
    public function test_120() {
        $renderer = XoopsCommentRenderer::Instance($this->tpl);
        $this->assertInstanceOf($this->myclass, $renderer);
    }
    
    public function test_140() {
        $renderer = XoopsCommentRenderer::getInstance($this->tpl);
        $this->assertInstanceOf($this->myclass, $renderer);
    }
    
    public function test_160() {
        $renderer = XoopsCommentRenderer::getInstance($this->tpl);
        $this->assertInstanceOf($this->myclass, $renderer);
        
        $comment1 = new XoopsComment();
        $comment1->setVar('com_status',XOOPS_COMMENT_ACTIVE);
        $comment2 = new XoopsComment();
        $comment2->setVar('com_status',XOOPS_COMMENT_ACTIVE);
        
        $tmp = array($comment1, $comment2);
        $renderer->setComments($tmp);
        
        $renderer->renderFlatView();
    }

}
