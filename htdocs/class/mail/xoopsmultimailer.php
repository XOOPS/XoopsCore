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
 * Xoops MultiMailer Base Class
 *
 * Mailer Class.
 *
 * At the moment, this does nothing but send email through PHP's "mail()" function,
 * but it has the abiltiy to do much more.
 *
 * If you have problems sending mail with "mail()", you can edit the member variables
 * to suit your setting. Later this will be possible through the admin panel.
 *
 * @todo Make a page in the admin panel for setting mailer preferences.
 *
 * PHP 5.3
 *
 * @category  Xoops\Class\Cache\MultiMailer
 * @package   MultiMailer
 * @author    Author: Jochen Bünnagel <job@buennagel.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   $Id$
 * @link      http://xoops.org
 * @since     2.6.0
 */
class XoopsMultiMailer extends PHPMailer
{
    /**
     * 'from' address
     *
     * @var string from address
     */
    public $From = '';

    /**
     * 'from' name
     *
     * @var string from name
     */
    public $FromName = '';

    // can be 'smtp', 'sendmail', or 'mail'
    /**
     * Method to be used when sending the mail.
     *
     * This can be:
     * <li>mail (standard PHP function 'mail()') (default)
     * <li>smtp    (send through any SMTP server, SMTPAuth is supported.
     * You must set {@link $Host}, for SMTPAuth also {@link $SMTPAuth},
     * {@link $Username}, and {@link $Password}.)
     * <li>sendmail (manually set the path to your sendmail program
     * to something different than 'mail()' uses in {@link $Sendmail})
     *
     * @var string type of mailer
     */
    public $Mailer = 'mail';

    /**
     * set if $Mailer is 'sendmail'
     *
     * Only used if {@link $Mailer} is set to 'sendmail'.
     * Contains the full path to your sendmail program or replacement.
     *
     * @var string sendmail configuration
     */
    public $Sendmail = '/usr/sbin/sendmail';

    /**
     * SMTP Host.
     *
     * Only used if {@link $Mailer} is set to 'smtp'
     *
     * @var string SMTP host name
     */
    public $Host = '';

    /**
     * Does your SMTP host require SMTPAuth authentication?
     *
     * @var boolean authorized?
     */
    public $SMTPAuth = false;

    /**
     * Username for authentication with your SMTP host.
     *
     * Only used if {@link $Mailer} is 'smtp' and {@link $SMTPAuth} is TRUE
     *
     * @var string user name for SMTP authentication
     */
    public $Username = '';

    /**
     * Password for SMTPAuth.
     *
     * Only used if {@link $Mailer} is 'smtp' and {@link $SMTPAuth} is TRUE
     *
     * @var string password for smtp authentication
     */
    public $Password = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $xoops = Xoops::getInstance();
        $xoopsMailerConfig = $xoops->getConfigs();
        $this->From = $xoopsMailerConfig['from'];
        if ($this->From == '') {
            $this->From = $xoops->getConfig('adminmail');
        }
        $this->Sender = $this->From;
        if ($xoopsMailerConfig['mailmethod'] == 'smtpauth') {
            $this->Mailer = 'smtp';
            $this->SMTPAuth = true;
            // TODO: change value type of xoopsConfig 'smtphost' from array to text
            $this->Host = implode(';', $xoopsMailerConfig['smtphost']);
            $this->Username = $xoopsMailerConfig['smtpuser'];
            $this->Password = $xoopsMailerConfig['smtppass'];
        } else {
            $this->Mailer = $xoopsMailerConfig['mailmethod'];
            $this->SMTPAuth = false;
            $this->Sendmail = $xoopsMailerConfig['sendmailpath'];
            $this->Host = implode(';', $xoopsMailerConfig['smtphost']);
        }
        $this->PluginDir = XOOPS_ROOT_PATH . '/class/mail/phpmailer/';
    }

    /**
     * Formats an address correctly. This overrides the default addr_format method which
     * does not seem to encode $FromName correctly
     *
     * This function over rides one in PHPmailer
     *
     * @param $addr address to send to
     *
     * @return string properly formatted address
     */
    public function AddrFormat($addr)
    {
        if (empty($addr[1])) {
            $formatted = $addr[0];
        } else {
            $formatted = sprintf('%s <%s>', '=?' . $this->CharSet . '?B?' . base64_encode($addr[1]) . '?=', $addr[0]);
        }
        return $formatted;
    }

    /**
     * Sends mail via SMTP using PhpSMTP (Author:
     * Chris Ryan).  Returns bool.  Returns false if there is a
     * bad MAIL FROM, or DATA input.
     * Rebuild Header if there is a bad RCPT
     *
     * This function over rides one in PHPmailer
     *
     * @param $header mail header
     * @param $body   mail body
     *
     * @return bool
     */
    public function SmtpSend($header, $body)
    {
        if (!XoopsLoad::fileExists($file = $this->PluginDir . 'class.smtp.php')) {
            trigger_error(
                'Required File  ' . $file . ' was not found in file ' . __FILE__ . ' at line ' . __LINE__,
                E_USER_WARNING
            );
            return false;
        }
        include_once $file;

        $error = '';
        $bad_rcpt = array();
        if (!$this->SmtpConnect()) {
            return false;
        }

        $smtp_from = ($this->Sender == '') ? $this->From : $this->Sender;
        if (!$this->smtp->Mail($smtp_from)) {
            $error = $this->Lang("from_failed") . $smtp_from;
            $this->SetError($error);
            $this->smtp->Reset();
            return false;
        }
        // Attempt to send attach all recipients
        for ($i = 0; $i < count($this->to); $i++) {
            if (!$this->smtp->Recipient($this->to[$i][0])) {
                $bad_rcpt[] = $this->to[$i][0];
                unset($this->to[$i]);
            }
        }
        for ($i = 0; $i < count($this->cc); $i++) {
            if (!$this->smtp->Recipient($this->cc[$i][0])) {
                $bad_rcpt[] = $this->cc[$i][0];
                unset($this->cc[$i]);
            }
        }
        for ($i = 0; $i < count($this->bcc); $i++) {
            if (!$this->smtp->Recipient($this->bcc[$i][0])) {
                $bad_rcpt[] = $this->bcc[$i][0];
                unset($this->bcc[$i]);
            }
        }
        // Create error message
        $count = count($bad_rcpt);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                if ($i != 0) {
                    $error .= ', ';
                }
                $error .= $bad_rcpt[$i];
            }
            // To rebuild a correct header, it should to rebuild a correct adress array
            $this->to = array_values($this->to);
            $this->cc = array_values($this->cc);
            $this->bcc = array_values($this->bcc);
            $header = $this->CreateHeader();

            $error = $this->Lang('recipients_failed') . $error;
            $this->SetError($error);
        }
        if (!$this->smtp->Data($header . $body)) {
            $this->SetError($this->Lang('data_not_accepted'));
            $this->smtp->Reset();
            return false;
        }
        if ($this->SMTPKeepAlive == true) {
            $this->smtp->Reset();
        } else {
            $this->SmtpClose();
        }
        return true;
    }
}
