<?php
require_once(__DIR__.'/../../../init_new.php');

use Xoops\Core\PreloadItem;

class PreloadItemTest extends \PHPUnit\Framework\TestCase
{
    public function test___construct()
	{
		$instance = new PreloadItem();
		$this->assertInstanceOf('\Xoops\Core\PreloadItem', $instance);
    }

}
