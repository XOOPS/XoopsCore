<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Database\Connection;

/**
 * Private message module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         pm
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

/**
 * @package         pm
 *
 * @author          Kazumi Ono    <onokazu@xoops.org>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 */
class PmMessage extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('msg_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('msg_image', XOBJ_DTYPE_OTHER, 'icon1.gif', false, 100);
        $this->initVar('subject', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('from_userid', XOBJ_DTYPE_INT, null, true);
        $this->initVar('to_userid', XOBJ_DTYPE_INT, null, true);
        $this->initVar('msg_time', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('msg_text', XOBJ_DTYPE_TXTAREA, null, true);
        $this->initVar('read_msg', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('from_delete', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('to_delete', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('from_save', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('to_save', XOBJ_DTYPE_INT, 0, false);
    }

}

class PmMessageHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|Connection $db
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, "priv_msgs", 'PmMessage', 'msg_id', 'subject');
    }

    /**
     * Mark a message as read
     *
     * @param XoopsObject|PmMessage $pm
     * @param int $val
     * @return bool
     */
    public function setRead(PmMessage $pm, $val = 1)
    {
        return $this->updateAll('read_msg', intval($val), new Criteria('msg_id', $pm->getVar('msg_id')), true);
    }

    /**
     * Mark a message as from_delete = 1 or removes it if the recipient has also deleted it
     *
     * @param XoopsObject|PmMessage $pm
     * @param int $val
     * @return bool
     */
    public function setFromdelete(PmMessage $pm, $val = 1)
    {
        if ($pm->getVar('to_delete') == 0) {
            return $this->updateAll('from_delete', intval($val), new Criteria('msg_id', $pm->getVar('msg_id')));
        } else {
            return parent::delete($pm);
        }
    }

    /**
     * Mark a message as to_delete = 1 or removes it if the sender has also deleted it or sent by anonymous
     *
     * @param XoopsObject|PmMessage $pm
     * @param int $val
     * @return bool
     */
    public function setTodelete(PmMessage $pm, $val = 1)
    {
        if ($pm->getVar('from_delete') == 0 && $pm->getVar('from_userid') == 0) {
            return $this->updateAll('to_delete', intval($val), new Criteria('msg_id', $pm->getVar('msg_id')));
        } else {
            return parent::delete($pm);
        }
    }

    /**
     * Mark a message as from_save = 1
     *
     * @param XoopsObject|PmMessage $pm
     * @param int $val
     * @return bool
     */
    public function setFromsave(PmMessage $pm, $val = 1)
    {
        return $this->updateAll('from_save', intval($val), new Criteria('msg_id', $pm->getVar('msg_id')));
    }

    /**
     * Mark a message as to_save = 1
     *
     * @param XoopsObject|PmMessage $pm
     * @param int $val
     * @return bool
     */
    public function setTosave(PmMessage $pm, $val = 1)
    {
        return $this->updateAll('to_save', intval($val), new Criteria('msg_id', $pm->getVar('msg_id')));
    }

    /**
     * get user's message count in savebox
     *
     * @param XoopsUser|null $user
     * @return int
     */
    public function getSavecount(XoopsUser $user = null)
    {
        $xoops = Xoops::getInstance();
        if (!is_object($user)) {
            $user = $xoops->user;
        }
        $crit_to = new CriteriaCompo(new Criteria('to_delete', 0));
        $crit_to->add(new Criteria('to_save', 1));
        $crit_to->add(new Criteria('to_userid', $user->getVar('uid')));
        $crit_from = new CriteriaCompo(new Criteria('from_delete', 0));
        $crit_from->add(new Criteria('from_save', 1));
        $crit_from->add(new Criteria('from_userid', $user->getVar('uid')));
        $criteria = new CriteriaCompo($crit_to);
        $criteria->add($crit_from, "OR");
        return $this->getCount($criteria);
    }

    /**
     * Send a message to user's email
     *
     * @param XoopsObject|PmMessage $pm
     * @param null|XoopsUser $user
     * @return bool
     */
    public function sendEmail(PmMessage $pm, XoopsUser $user = null)
    {
        $xoops = Xoops::getInstance();
        if (!is_object($user)) {
            $user = $xoops->user;
        }
        $msg = sprintf(_PM_EMAIL_DESC, $user->getVar("uname"));
        $msg .= "\n\n";
        $msg .= XoopsLocale::formatTimestamp($pm->getVar("msg_time"));
        $msg .= "\n";
        $from = new XoopsUser($pm->getVar("from_userid"));
        $to = new XoopsUser($pm->getVar("to_userid"));
        $msg .= sprintf(_PM_EMAIL_FROM, $from->getVar("uname") . " (" . XOOPS_URL . "/userinfo.php?uid=" . $pm->getVar("from_userid") . ")");
        $msg .= "\n";
        $msg .= sprintf(_PM_EMAIL_TO, $to->getVar("uname") . " (" . XOOPS_URL . "/userinfo.php?uid=" . $pm->getVar("to_userid") . ")");
        $msg .= "\n";
        $msg .= _PM_EMAIL_MESSAGE . ":\n";
        $msg .= "\n" . $pm->getVar("subject") . "\n";
        $msg .= "\n" . strip_tags(str_replace(array(
                                                   "<p>", "</p>", "<br />", "<br />"
                                              ), "\n", $pm->getVar("msg_text"))) . "\n\n";
        $msg .= "--------------\n";
        $msg .= $xoops->getConfig('sitename') . ": " . XOOPS_URL . "\n";

        $xoopsMailer = $xoops->getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setToEmails($user->getVar("email"));
        $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
        $xoopsMailer->setFromName($xoops->getConfig('sitename'));
        $xoopsMailer->setSubject(sprintf(_PM_EMAIL_SUBJECT, $pm->getVar("subject")));
        $xoopsMailer->setBody($msg);
        return $xoopsMailer->send();
    }

    /**
     * Get form for setting prune criteria
     *
     * @return Xoops\Form\ThemeForm
     **/
    public function getPruneForm()
    {
        $form = new Xoops\Form\ThemeForm(_PM_AM_PRUNE, 'form', 'prune.php', 'post', true);

        $form->addElement(new Xoops\Form\DateTime(_PM_AM_PRUNEAFTER, 'after'));
        $form->addElement(new Xoops\Form\DateTime(_PM_AM_PRUNEBEFORE, 'before'));
        $form->addElement(new Xoops\Form\RadioYesNo(_PM_AM_ONLYREADMESSAGES, 'onlyread', 1));
        $form->addElement(new Xoops\Form\RadioYesNo(_PM_AM_INCLUDESAVE, 'includesave', 0));
        $form->addElement(new Xoops\Form\RadioYesNo(_PM_AM_NOTIFYUSERS, 'notifyusers', 0));
        $form->addElement(new Xoops\Form\Hidden('op', 'prune'));
        $form->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));

        return $form;
    }
}
