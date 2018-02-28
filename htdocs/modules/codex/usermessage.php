<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Service\Data\Message;
use Xoops\Form\ThemeForm;
use Xoops\Form\TextArea;
use Xoops\Form\Button;
use Xmf\Request;

/**
 * @copyright 2012-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Laurent JEN - aka DuGris
 */

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

if (!$xoops->service('usermessage')->isAvailable()) {
    echo 'Please install a usermessage provider to view this demonstration.';
}

if ($xoops->isUser()) {
    Xmf\Debug::dump($xoops->user->uid());
    if ('POST' === Request::getMethod()) {
        $body = Request::getString('body', '');
        $body = empty($body) ? 'Not Specified' : $body;
        $message = new Message(
            $xoops->user->uid(),
            1,
            'Codex User Message Example',
            $body
        );

        $response = $xoops->service('usermessage')->sendMessage($message);
        if ($response->isSuccess()) {
            echo 'Message sent. Check your inbox.';
        } else {
            $errors = implode (', ', (array) $response->getErrorMessage());
            echo 'Your message was not sent<br>';
            echo $errors;
        }
    } else {
        $form = new ThemeForm('User Message Example', 'example', '', 'post', true, 'horizontal');
        $form->addElement(new TextArea('Body', 'body'), true);
        $form->addElement(new Button('', 'submit', 'Send Message', 'submit'));
        $form->display();
    }
} else {
    echo 'Please login to view this demonstration.';
}
// get a full img tag to show a QR code of a URL
//$response = $xoops->service('usermessage')
//    ->getImgTag('https://www.xoops.org/', array('alt' => 'QR code', 'title'=>'Xoops.org'))
//    ->getValue();


\Xoops\Utils::dumpFile(__FILE__);

$xoops->footer();
