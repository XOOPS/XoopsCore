<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Handlers\XoopsGroup;
use Xoops\Core\Kernel\Handlers\XoopsUser;

/**
 * XOOPS mailer
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 * @deprecated      use {@link XoopsMultiMailer} instead.
 */

/**
 * Class for sending mail.
 *
 * Changed to use the facilities of  {@link XoopsMultiMailer}
 *
 * @package class
 * @subpackage mail
 * @author Kazumi Ono <onokazu@xoops.org>
 */
class XoopsMailer
{
    /**
     * reference to a {@link XoopsMultiMailer}
     *
     * @var XoopsMultiMailer
     * @access protected
     * @since 21.02.2003 14:14:13
     */
    protected $multimailer;

    /**
     * sender email address
     *
     * @var string
     */
    private $fromEmail;

    /**
     * sender name
     *
     * @var string
     */
    private $fromName;

    /**
     * sender UID
     *
     * @var XoopsUser
     */
    private $fromUser;

    /**
     * array of user class objects
     *
     * @var array
     */
    private $toUsers;

    /**
     * array of email addresses
     *
     * @var array
     */
    private $toEmails;

    // private
    /**
     * custom headers
     *
     * @var array
     */
    private $headers;

    /**
     * subjet of mail
     *
     * @var string
     */
    private $subject;

    /**
     * body of mail
     *
     * @var string
     */
    private $body;

    /**
     * error messages
     *
     * @var array
     */
    private $errors;

    /**
     * messages upon success
     *
     * @var array
     */
    private $success;

    /**
     * @var bool
     */
    private $isMail;

    /**
     * @var bool
     */
    private $isPM;

    /**
     * @var array
     */
    private $assignedTags;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $templatedir;

    /**
     * @var string
     */
    protected $charSet = 'iso-8859-1';

    /**
     * @var string
     */
    protected $encoding = '8bit';

    /**
     * @var string
     */
    private $priority;

    /**
     * @var string
     */
    private $LE;

    /**
     * Constructor
     *
     * @return XoopsMailer
     */
    public function __construct()
    {
        $this->multimailer = new XoopsMultiMailer();
        $this->reset();
    }

    /**
     * reset all properties to default
     *
     * @param bool $value
     * @return void
     */
    public function setHTML($value = true)
    {
        $this->multimailer->isHTML($value);
    }

    /**
     * reset all properties to default
     *
     * @return void
     */
    public function reset()
    {
        $this->fromEmail = "";
        $this->fromName = "";
        $this->fromUser = null; // RMV-NOTIFY
        $this->priority = '';
        $this->toUsers = array();
        $this->toEmails = array();
        $this->headers = array();
        $this->subject = "";
        $this->body = "";
        $this->errors = array();
        $this->success = array();
        $this->isMail = false;
        $this->isPM = false;
        $this->assignedTags = array();
        $this->template = "";
        $this->templatedir = "";
        // Change below to \r\n if you have problem sending mail
        $this->LE = "\n";
    }

    /**
     * @param string $value
     * @return void
     */
    public function setTemplateDir($value = null)
    {
        $xoops = Xoops::getInstance();
        if ($value === null && $xoops->isModule()) {
            $value = $xoops->module->getVar('dirname', 'n');
        } else {
            $value = str_replace(DIRECTORY_SEPARATOR, "/", $value);
        }
        $this->templatedir = $value;
    }

    /**
     * @return bool|string
     */
    private function getTemplatePath()
    {
        $xoops = Xoops::getInstance();
        if (!$path = $this->templatedir) {
            $path = \XoopsBaseConfig::get('root-path') . "/locale/";
        } elseif (false === strpos($path, '/')) {
            $path = \XoopsBaseConfig::get('root-path') . "/modules/" . $path . "/locale/";
        } elseif (substr($path, -1, 1) !== "/") {
            $path .= "/";
        }
        if (XoopsLoad::fileExists($path . $xoops->getConfig('locale') . "/templates/" . $this->template)) {
            return $path . $xoops->getConfig('locale') . "/templates/" . $this->template;
        } elseif (XoopsLoad::fileExists($path . "en_US/templates/" . $this->template)) {
            return $path . "en_US/templates/" . $this->template;
        } elseif (XoopsLoad::fileExists($path . $this->template)) {
            return $path . $this->template;
        } else {
            return false;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setTemplate($value)
    {
        $this->template = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setFromEmail($value)
    {
        $this->fromEmail = trim($value);
    }

    /**
     * @param string $value
     * @return void
     */
    public function setFromName($value)
    {
        $this->fromName = trim($value);
    }

    /**
     * @param XoopsUser $user
     * @return void
     */
    public function setFromUser(XoopsUser $user)
    {
        $this->fromUser = $user;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setPriority($value)
    {
        $this->priority = trim($value);
    }

    /**
     * @param string $value
     * @return void
     */
    public function setSubject($value)
    {
        $this->subject = trim($value);
    }

    /**
     * @param string $value
     * @return void
     */
    public function setBody($value)
    {
        $this->body = trim($value);
    }

    /**
     * @return void
     */
    public function useMail()
    {
        $this->isMail = true;
    }

    /**
     * @return void
     */
    public function usePM()
    {
        $this->isPM = true;
    }

    /**
     * @param bool $debug
     * @return bool
     */
    public function send($debug = false)
    {
        $xoops = Xoops::getInstance();
        if ($this->body == "" && $this->template == "") {
            if ($debug) {
                $this->errors[] = XoopsLocale::E_MESSAGE_BODY_NOT_SET;
            }
            return false;
        } elseif ($this->template != "") {
            $path = $this->getTemplatePath();
            if (!($fd = @fopen($path, 'r'))) {
                if ($debug) {
                    $this->errors[] = XoopsLocale::E_TEMPLATE_FILE_NOT_OPENED;
                }
                return false;
            }
            $this->setBody(fread($fd, filesize($path)));
        }
        $headers = '';
        // for sending mail only
        if ($this->isMail || !empty($this->toEmails)) {
            if (!empty($this->priority)) {
                $this->headers[] = "X-Priority: " . $this->priority;
            }
            // $this->headers[] = "X-Mailer: PHP/".phpversion();
            // $this->headers[] = "Return-Path: ".$this->fromEmail;
            $headers = join($this->LE, $this->headers);
        }
        // TODO: we should have an option of no-reply for private messages and emails
        // to which we do not accept replies.  e.g. the site admin doesn't want a
        // a lot of message from people trying to unsubscribe.  Just make sure to
        // give good instructions in the message.
        // add some standard tags (user-dependent tags are included later)

        $this->assign('X_ADMINMAIL', $xoops->getConfig('adminmail'));
        $this->assign('X_SITENAME', $xoops->getConfig('sitename'));
        $this->assign('X_SITEURL', \XoopsBaseConfig::get('url') . "/");
        // TODO: also X_ADMINNAME??
        // TODO: X_SIGNATURE, X_DISCLAIMER ?? - these are probably best
        // done as includes if mail templates ever get this sophisticated
        // replace tags with actual values
        foreach ($this->assignedTags as $k => $v) {
            $this->body = str_replace("{" . $k . "}", $v, $this->body);
            $this->subject = str_replace("{" . $k . "}", $v, $this->subject);
        }
        $this->body = str_replace("\r\n", "\n", $this->body);
        $this->body = str_replace("\r", "\n", $this->body);
        $this->body = str_replace("\n", $this->LE, $this->body);
        // send mail to specified mail addresses, if any
        foreach ($this->toEmails as $mailaddr) {
            if (!$this->sendMail($mailaddr, $this->subject, $this->body, $headers)) {
                if ($debug) {
                    $this->errors[] = sprintf(XoopsLocale::EF_EMAIL_NOT_SENT_TO, $mailaddr);
                }
            } else {
                if ($debug) {
                    $this->success[] = sprintf(XoopsLocale::SF_EMAIL_SENT_TO, $mailaddr);
                }
            }
        }
        // send message to specified users, if any
        // NOTE: we don't send to LIST of recipients, because the tags
        // below are dependent on the user identity; i.e. each user
        // receives (potentially) a different message
        foreach ($this->toUsers as $user) {
            /* @var $user XoopsUser */
            // set some user specific variables
            $subject = str_replace("{X_UNAME}", $user->getVar("uname"), $this->subject);
            $text = str_replace("{X_UID}", $user->getVar("uid"), $this->body);
            $text = str_replace("{X_UEMAIL}", $user->getVar("email"), $text);
            $text = str_replace("{X_UNAME}", $user->getVar("uname"), $text);
            $text = str_replace("{X_UACTLINK}", \XoopsBaseConfig::get('url') . "/register.php?op=actv&id=" . $user->getVar("uid") . "&actkey=" . $user->getVar('actkey'), $text);
            // send mail
            if ($this->isMail) {
                if (!$this->sendMail($user->getVar("email"), $subject, $text, $headers)) {
                    if ($debug) {
                        $this->errors[] = sprintf(XoopsLocale::EF_EMAIL_NOT_SENT_TO, $user->getVar("uname"));
                    }
                } else {
                    if ($debug) {
                        $this->success[] = sprintf(XoopsLocale::SF_EMAIL_SENT_TO, $user->getVar("uname"));
                    }
                }
            }
            // send private message
            if ($this->isPM) {
                if (!$this->sendPM($user->getVar("uid"), $subject, $text)) {
                    if ($debug) {
                        $this->errors[] = sprintf(XoopsLocale::EF_PRIVATE_MESSAGE_NOT_SENT_TO, $user->getVar("uname"));
                    }
                } else {
                    if ($debug) {
                        $this->success[] = sprintf(XoopsLocale::SF_PRIVATE_MESSAGE_SENT_TO, $user->getVar("uname"));
                    }
                }
            }
            flush();
        }
        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }

    /**
     * @param int $uid
     * @param string $subject
     * @param string $body
     * @return bool
     */
    private function sendPM($uid, $subject, $body)
    {
        $xoops = Xoops::getInstance();
        $pm_handler = $xoops->getHandlerPrivateMessage();
        $pm = $pm_handler->create();
        $pm->setVar("subject", $subject);
        // RMV-NOTIFY
        $pm->setVar('from_userid', !empty($this->fromUser) ? $this->fromUser->getVar('uid') : (!$xoops->isUser() ? 1
                : $xoops->user->getVar('uid')));
        $pm->setVar("msg_text", $body);
        $pm->setVar("to_userid", $uid);
        $pm->setVar('msg_time', time());
        if (!$pm_handler->insert($pm)) {
            return false;
        }
        return true;
    }

    /**
     * Send email
     *
     * Uses the new XoopsMultiMailer
     *
     * @param string $email
     * @param string $subject
     * @param string $body
     * @param array $headers
     * @return bool
     */
    private function sendMail($email, $subject, $body, $headers)
    {
        $subject = $this->encodeSubject($subject);
        $this->encodeBody($body);
        $this->multimailer->ClearAllRecipients();
        $this->multimailer->AddAddress($email);
        $this->multimailer->Subject = $subject;
        $this->multimailer->Body = $body;
        $this->multimailer->CharSet = $this->charSet;
        $this->multimailer->Encoding = $this->encoding;
        if (!empty($this->fromName)) {
            $this->multimailer->FromName = $this->encodeFromName($this->fromName);
        }
        if (!empty($this->fromEmail)) {
            $this->multimailer->Sender = $this->multimailer->From = $this->fromEmail;
        }

        $this->multimailer->ClearCustomHeaders();
        foreach ($this->headers as $header) {
            $this->multimailer->AddCustomHeader($header);
        }
        if (!$this->multimailer->Send()) {
            $this->errors[] = $this->multimailer->ErrorInfo;
            return false;
        }
        return true;
    }

    /**
     * @param bool $ashtml
     * @return string
     */
    public function getErrors($ashtml = true)
    {
        if (!$ashtml) {
            return $this->errors;
        } else {
            $ret = "";
            if (!empty($this->errors)) {
                $ret = "<h4>" . XoopsLocale::ERRORS . "</h4>";
                foreach ($this->errors as $error) {
                    $ret .= $error . "<br />";
                }
            }
            return $ret;
        }
    }

    // public
    function getSuccess($ashtml = true)
    {
        if (!$ashtml) {
            return $this->success;
        } else {
            $ret = "";
            if (!empty($this->success)) {
                foreach ($this->success as $suc) {
                    $ret .= $suc . "<br />";
                }
            }
            return $ret;
        }
    }

    /**
     * @param string|array $tag
     * @param null $value
     * @return void
     */
    public function assign($tag, $value = null)
    {
        if (is_array($tag)) {
            foreach ($tag as $k => $v) {
                $this->assign($k, $v);
            }
        } else {
            if (!empty($tag) && isset($value)) {
                $tag = strtoupper(trim($tag));
                // RMV-NOTIFY
                // TEMPORARY FIXME: until the X_tags are all in here
                // if ( substr($tag, 0, 2) != "X_" ) {
                $this->assignedTags[$tag] = $value;
                // }
            }
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function addHeaders($value)
    {
        $this->headers[] = trim($value) . $this->LE;
    }

    /**
     * @param $email
     * @return void
     */
    public function setToEmails($email)
    {
        if (!is_array($email)) {
            $xoops = Xoops::getInstance();
            if ($xoops->checkEmail($email)) {
                array_push($this->toEmails, $email);
            }
        } else {
            foreach ($email as $e) {
                $this->setToEmails($e);
            }
        }
    }

    /**
     * @param XoopsUser|array $user
     * @return void
     */
    public function setToUsers($users)
    {
        if ($users instanceof XoopsUser) {
            array_push($this->toUsers, $users);
        } elseif (is_array($users)) {
            foreach ($users as $u) {
                $this->setToUsers($u);
            }
        }
    }

    /**
     * @param XoopsGroup $group
     * @return void
     */
    public function setToGroups($groups)
    {
        if ($groups instanceof XoopsGroup) {
            $this->setToUsers(Xoops::getInstance()
                    ->getHandlerMember()
                    ->getUsersByGroup($groups->getVar('groupid'), true));

        } elseif (is_array($groups)) {
            foreach ($groups as $g) {
                $this->setToGroups($g);
            }
        }
    }

    /**
     * abstract, to be overridden by lang specific mail class, if needed
     *
     * @param $text
     * @return
     */
    public function encodeFromName($text)
    {
        return $text;
    }

    /**
     * abstract, to be overridden by lang specific mail class, if needed
     *
     * @param string $text
     * @return string
     */
    public function encodeSubject($text)
    {
        return $text;
    }

    /**
     * abstract, to be overridden by lang specific mail class, if needed
     *
     * @param string $text
     * @return void
     */
    public function encodeBody(&$text)
    {
    }
}
