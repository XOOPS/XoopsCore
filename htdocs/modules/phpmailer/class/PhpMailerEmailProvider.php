<?php
/*
 You may not change or alter any portion of this comment or credits of supporting
 developers from this source code or any supporting source code which is considered
 copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Module\Helper;
use Xoops\Core\Service\AbstractContract;
use Xoops\Core\Service\Contract\EmailInterface;
use Xoops\Core\Service\Response;
use Xoops\Core\Service\Data\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * phpmailer module
 *
 * @copyright 2018 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Richard Griffith <richard@geekwright.com>
 * @link      https://xoops.org
 */
class PhpMailerEmailProvider extends AbstractContract implements EmailInterface
{
    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    public function getName()
    {
        return 'phpmaileremail';
    }

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Use PHPMailer for email.';
    }

    /**
     * @param \Xoops\Core\Service\Response     $response
     * @param \Xoops\Core\Service\Data\Message $email
     *
     * @return void - reports success or failure through $response->success
     */
    public function sendEmail(Response $response, Email $email)
    {
        try {
            $mailer = $this->setupMailer();
            $this->mapEmail($email, $mailer);
            $mailer->send();
        } catch (Exception $e) {
            $response->setSuccess(false)->addErrorMessage($e->getMessage());
        } catch (\Throwable $e) {
            $response->setSuccess(false)->addErrorMessage($e->getMessage());
            return;
        }
    }

    protected function mapEmail(Email $email, PHPMailer $mailer)
    {
        // Addresses
        $address = $email->getFromAddress();
        $mailer->setFrom($address->getEmail(), (string) $address->getDisplayName());

        $list = $email->getToAddresses();
        foreach ($list->getEachAddress() as $address) {
            $mailer->addAddress($address->getEmail(), (string) $address->getDisplayName());
        }

        $list = $email->getReplyToAddresses();
        if (null !== $list) {
            foreach ($list->getEachAddress() as $address) {
                $mailer->addReplyTo($address->getEmail(), (string)$address->getDisplayName());
            }
        }

        $list = $email->getCcAddresses();
        if (null !== $list) {
            foreach ($list->getEachAddress() as $address) {
                $mailer->addCC($address->getEmail(), (string)$address->getDisplayName());
            }
        }

        $list = $email->getBccAddresses();
        if (null !== $list) {
            foreach ($list->getEachAddress() as $address) {
                $mailer->addBCC($address->getEmail(), (string)$address->getDisplayName());
            }
        }

        // Attachments
        $attachmentSet = $email->getAttachments();
        if (null !== $attachmentSet) {
            foreach ($attachmentSet->getEachAttachment() as $attachment) {
                $file = $attachment->getFilename();
                $body = $attachment->getStringBody();
                $name = (string) $attachment->getName();
                $type = (string) $attachment->getMimeType();
                $inline = $attachment->getInlineAttribute();
                if (null !== $file && !$inline) {
                    $mailer->addAttachment($file, $name, 'base64', $type);
                } elseif (null === $file && !$inline) {
                    $mailer->addStringAttachment($body, $name, 'base64', $type);
                } elseif (null !== $file && $inline) {
                    $mailer->addEmbeddedImage($file, $name, $name, 'base64', $type);
                } elseif (null === $file && $inline) {
                    $mailer->addStringEmbeddedImage($body, $name, $name, 'base64', $type);
                }
            }
        }

        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = $email->getSubject();

        if (null !== $email->getHtmlBody()) {
            $mailer->Body = $email->getHtmlBody();
            $mailer->AltBody = $email->getBody();
            $mailer->isHTML(true);
            return $mailer;
        }

        $mailer->isHTML(false);
        $mailer->Body= $email->getBody();

        return $mailer;
    }

    /**
     * Get a mailer instance with configured transport
     * @return \PHPMailer\PHPMailer\PHPMailer
     */
    protected function setupMailer() : PHPMailer
    {
        $mailer = new PHPMailer(true);
        $mailer->Debugoutput = \Xoops::getInstance()->logger();
        $helper = Helper::getHelper('phpmailer');
        // mailmethod = 'mail', 'sendmail', 'smtp', 'smtpauth'

        $mailmethod = $helper->getConfig('mailmethod', 'mail');
        switch ($mailmethod) {
            case 'sendmail':
                $mailer->isSendmail();
                $mailer->Sendmail = $helper->getConfig('sendmailpath', $mailer->Sendmail);
                break;
            case 'smtpauth':
                $mailer->SMTPAuth = true;
                $mailer->Username = $helper->getConfig('smtp_user', '');
                $mailer->Password = $helper->getConfig('smtp_pass', '');
            // fallthrough
            case 'smtp':
                $mailer->isSMTP();
                $mailer->Host = $helper->getConfig('smtp_host', $mailer->Host);
                $mailer->SMTPAutoTLS = (bool) $helper->getConfig('smtp_usetls', true);
                $mailer->SMTPDebug = (int) $helper->getConfig('smtp_debug', 0);
                break;
            case 'mail':
            default:
                $mailer->isMail();
                break;
        }
        return $mailer;
    }
}
