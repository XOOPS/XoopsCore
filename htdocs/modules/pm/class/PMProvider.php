<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Module\Helper;
use Xoops\Core\Service\AbstractContract;
use Xoops\Core\Service\Contract\UserMessageInterface;
use Xoops\Core\Service\Data\Message;
use Xoops\Core\Service\Response;

/**
 * PM provider for service manager
 *
 * @category  class
 * @package   PMProvider
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 * @since     2.6.0
 */
class PMProvider extends AbstractContract implements UserMessageInterface
{
    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    public function getName()
    {
        return 'pm';
    }

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Use PM for user messsages.';
    }

    public function sendMessage(Response $response, Message $message)
    {
        $pmHandler = Helper::getHelper('pm')->getHandler('message');
        /** @var \PmMessage */
        $pm = $pmHandler->create();
        $pm->setVar('msg_time', time());

        try {
            $pm->setVar('subject', $message->getSubject());
            $pm->setVar('msg_text', $message->getBody());
            $pm->setVar('to_userid', $message->getToId());
            $pm->setVar('from_userid', $message->getFromId());
        } catch (\LogicException $e) {
            $response->setSuccess(false)->addErrorMessage($e->getMessage());

            return;
        }
        //PMs are by default not saved in outbox
        //$pm->setVar('from_delete', 0);

        if (false === $pmHandler->insert($pm)) {
            $response->setSuccess(false)->addErrorMessage($pm->getErrors());
        }
    }
}
