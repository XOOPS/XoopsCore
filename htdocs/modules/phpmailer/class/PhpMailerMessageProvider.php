<?php
/*
 You may not change or alter any portion of this comment or credits of supporting
 developers from this source code or any supporting source code which is considered
 copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Assert;
use Xoops\Core\Kernel\Handlers\XoopsUser;
use Xoops\Core\Service\AbstractContract;
use Xoops\Core\Service\Contract\UserEmailMessageInterface;
use Xoops\Core\Service\Data\Email;
use Xoops\Core\Service\Data\EmailAddress;
use Xoops\Core\Service\Data\Message;
use Xoops\Core\Service\Response;

/**
 * phpmailer module
 *
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Richard Griffith <richard@geekwright.com>
 * @link      https://xoops.org
 */
class PhpMailerMessageProvider extends AbstractContract implements UserEmailMessageInterface
{
    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    public function getName()
    {
        return 'phpmailermessage';
    }

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    public function getDescription()
    {
        return 'User messages by email using PHPMailer.';
    }

    public function sendMessage(Response $response, Message $message)
    {
        try {
            $email = new Email(
                $message->getSubject(),
                $message->getBody(),
                $this->getEmailAddressByUser($message->getFromId()),
                $this->getEmailAddressByUser($message->getToId())
            );
        } catch (\InvalidArgumentException | \LogicException $e) {
            $response->setSuccess(false)->addErrorMessage($e->getMessage());

            return;
        }
        // Relay to Email Service
        $emailResponse = \Xoops::getInstance()->service('email')->sendEmail($email);
        if (!$emailResponse->isSuccess()) {
            $response->setSuccess(false);
            $response->addErrorMessage($emailResponse->getErrorMessage());
        }
    }

    /**
     * @throws \InvalidArgumentException -- bad userid
     * @return \Xoops\Core\Service\Data\EmailAddress
     */
    protected function getEmailAddressByUser(int $userid): EmailAddress
    {
        $userHandler = \Xoops::getInstance()->getHandlerMember();
        $user = $userHandler->getUser($userid);
        Assert::isInstanceOf($user, XoopsUser::class);
        $name = empty($user->name()) ? $user->uname() : $user->name();

        return new EmailAddress($user->email(), $name);
    }
}
