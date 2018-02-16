<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use PHPMailer\PHPMailer\PHPMailer;

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
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
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
        parent::__construct();
        $xoops = Xoops::getInstance();
        $this->From = $xoops->getConfig('from');
        if ($this->From == '') {
            $this->From = $xoops->getConfig('adminmail');
        }
        $this->Sender = $this->From;
        if ('smtpauth' === $xoops->getConfig('mailmethod')) {
            $this->Mailer = 'smtp';
            $this->SMTPAuth = true;
            $this->Username = $xoops->getConfig('smtpuser');
            $this->Password = $xoops->getConfig('smtppass');
        } else {
            $this->Mailer = $xoops->getConfig('mailmethod');
            $this->SMTPAuth = false;
            $this->Sendmail = $xoops->getConfig('sendmailpath');
        }
        // TODO: change value type of xoopsConfig 'smtphost' from array to text
        $smtphost = $xoops->getConfig('smtphost');
        $this->Host = is_array($smtphost) ? implode(';', $smtphost) : $smtphost;
        //$this->PluginDir = \XoopsBaseConfig::get('root-path') . '/class/mail/phpmailer/';
    }
}
