<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xmf\Request;
use Xoops\Core\Service\Data\Email;
use Xoops\Core\Service\Data\EmailAddress;
use Xoops\Form\Button;
use Xoops\Form\TextArea;
use Xoops\Form\ThemeForm;

/**
 * @copyright 2012-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Laurent JEN - aka DuGris
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

if (!$xoops->service('email')->isAvailable()) {
    echo 'Please install and configure an email provider to view this demonstration.';
}

if ($xoops->isUser()) {
    if ('POST' === Request::getMethod()) {
        try {
            $fromAddress = new EmailAddress($xoops->getConfig('from'), $xoops->getConfig('fromname'));
            $name = empty($xoops->user->name()) ? $xoops->user->uname() : $xoops->user->name();
            $toAddress = new EmailAddress($xoops->user->email(), $name);

            $body = Request::getString('body', '');
            $body = empty($body) ? 'Not Specified' : $body;
            $email = new Email(
                'Codex Email Example',
                $body,
                $fromAddress,
                $toAddress
            );
            $response = $xoops->service('email')->sendEmail($email);
            if ($response->isSuccess()) {
                echo 'Message sent. Check your inbox.';
            } else {
                $errors = implode(', ', (array)$response->getErrorMessage());
                echo 'Your message was not sent<br>';
                echo $errors;
            }
        } catch (\InvalidArgumentException $e) {
            echo 'Message was not sent. ' . $e->getMessage();
        }
    } else {
        $form = new ThemeForm('Email Service Example', 'example', '', 'post', true, 'horizontal');
        $form->addElement(new TextArea('Body', 'body'), true);
        $form->addElement(new Button('', 'submit', 'Send Message', 'submit'));
        $form->display();
    }
} else {
    echo 'Please login to view this demonstration.';
}

\Xoops\Utils::dumpFile(__FILE__);

$xoops->footer();
