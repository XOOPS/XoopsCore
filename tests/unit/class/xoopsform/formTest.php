<?php
require_once(dirname(__FILE__).'/../../init_new.php');

class XoopsFormInstance extends XoopsForm
{
    function render() {}
}
/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormInstance';

    public function test___construct()
	{
		$instance = new $this->myClass('', '', '');
        $this->assertInstanceOf('Xoops\\Form\\Form', $instance);
    }

}
