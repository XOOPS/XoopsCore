<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsMember;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MemberTest extends \PHPUnit_Framework_TestCase
{
    var $myclass='XoopsMember';

    public function setUp()
	{
    }

    public function test___construct()
	{
    }

}
