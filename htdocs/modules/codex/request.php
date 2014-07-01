<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

$request = Xoops_Request::getInstance();

Xoops_Utils::dumpVar($request->getParam());
$result['id'] = $request->asInt('id', 13);
$result['string'] = $request->asStr('string', 'defaultValueHere');
$result['bool'] = $request->asBool('bool', false);
$result['order'] = $request->asStr('order', 'ASC', array('ASC', 'DESC'));
$result['url'] = $request->getUrl();
$result['uri'] = $request->getUri();
$result['referer'] = $request->getReferer();
$result['phpsessid_cookie'] = $request->getCookie('PHPSESSID');
$result['ip'] = $request->getClientIp();
$result['isget'] = $request->is('get');
$result['ispost'] = $request->is('post');
$result['ismobile'] = $request->is('mobile');
$result['isrobot'] = $request->is('robot');
$result['files'] = $request->getFiles('file_identifier');

Xoops_Utils::dumpVar($result);

echo '<a href="?id=12&string=I love you&bool=everythingsistrue&order=DESC">Good uri</a>';
echo ' - <a href="?id=test&order=DESCENDING">Bad uri</a>';

// Form
$form = new XoopsSimpleForm('', 'form_id', 'request.php?id=666', true);
$form->setExtra('enctype="multipart/form-data"');

$code = new XoopsFormText('String', 'string', 2, 25, '','string...');
$code->setDescription('Description text');
$code->setPattern('^.{3,}$', 'You need at least 3 characters');
$code->setDatalist(array('list 1','list 2','list 3'));
$form->addElement($code, true);

$select = new XoopsFormSelect('Select', 'id', '', 1, false);
$select->addOption(1, 'Select 1');
$select->addOption(2, 'Select 2');
$select->addOption('somebad id here', 'Select with bad id');
$select->setDescription('Description Select');
$select->setClass('span2');
$form->addElement($select, true);

$file = new XoopsFormFile('File', 'file_identifier', 500000);
$file->setDescription('Description File');
$form->addElement($file, true);

$button = new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
$form->addElement($button);
$form->display();

Xoops_Utils::dumpFile(__FILE__ );
$xoops->footer();
