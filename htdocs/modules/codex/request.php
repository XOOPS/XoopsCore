<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Request;

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
// Warning: code depending on Xoops\Core\HttpRequest may need to change
$request = \Xoops\Core\HttpRequest::getInstance();
$xoops->header();

Xoops_Utils::dumpVar(Request::get());
$result['id'] = Request::getInt('id', 13);
$result['string'] = Request::getString('string', 'defaultValueHere');
$result['bool'] = Request::getBool('bool', false);
$result['order'] = Request::getString('order', 'ASC');
$result['url'] = $request->getUrl();
$result['uri'] = $request->getUri();
$result['referer'] = $request->getReferer();
$result['phpsessid_cookie'] = Request::getString('PHPSESSID', '', 'cookie');
$result['ip'] = $request->getClientIp();
$result['isget'] = 'GET' == Request::getMethod();
$result['ispost'] = 'POST' == Request::getMethod();
$result['ismobile'] = $request->is('mobile');
$result['isrobot'] = $request->is('robot');
$result['files'] = Request::getArray('file_identifier', array(), 'files');

Xoops_Utils::dumpVar($result);

echo '<a href="?id=12&string=I love you&bool=everythingsistrue&order=DESC">Good uri</a>';
echo ' - <a href="?id=test&order=DESCENDING">Bad uri</a>';

// Form
$form = new Xoops\Form\SimpleForm('', 'form_id', 'request.php?id=666', true);
$form->setExtra('enctype="multipart/form-data"');

$code = new Xoops\Form\Text('String', 'string', 2, 25, '', 'string...');
$code->setDescription('Description text');
$code->setPattern('^.{3,}$', 'You need at least 3 characters');
$code->setDatalist(array('list 1','list 2','list 3'));
$form->addElement($code, true);

$select = new Xoops\Form\Select('Select', 'id', '', 1, false);
$select->addOption(1, 'Select 1');
$select->addOption(2, 'Select 2');
$select->addOption('somebad id here', 'Select with bad id');
$select->setDescription('Description Select');
$select->setClass('span2');
$form->addElement($select, true);

$file = new Xoops\Form\File('File', 'file_identifier');
$file->setDescription('Description File');
$form->addElement($file, true);

$button = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
$form->addElement($button);
$form->display();

Xoops_Utils::dumpFile(__FILE__);
$xoops->footer();
