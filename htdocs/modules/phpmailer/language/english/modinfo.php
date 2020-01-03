<?php
/*
 You may not change or alter any portion of this comment or credits of supporting
 developers from this source code or any supporting source code which is considered
 copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Richard Griffith <richard@geekwright.com>
 */

define('_MI_PHPMAILER_NAME','PHPMailer');
define('_MI_PHPMAILER_DESC','Use PHPMailer for Email service');

define('_MI_PHPMAILER_CONF_SMTPHOST', 'SMTP host(s)');
define('_MI_PHPMAILER_CONF_SMTPHOST_DESC', 'List of SMTP servers to try to connect to separated by a ";". Example: localhost:25;tls://smtp.example.com:587');
define('_MI_PHPMAILER_CONF_SMTPPASS', 'SMTPAuth password');
define('_MI_PHPMAILER_CONF_SMTPPASS_DESC', 'Password to connect to an SMTP host with SMTPAuth.');
define('_MI_PHPMAILER_CONF_SMTPUSER', 'SMTPAuth username');
define('_MI_PHPMAILER_CONF_SMTPUSER_DESC', 'Username to connect to an SMTP host with SMTPAuth.');
define('_MI_PHPMAILER_CONF_MAILER', 'Email Setup');
define('_MI_PHPMAILER_CONF_MAILERMETHOD', 'Email delivery method');
define('_MI_PHPMAILER_CONF_MAILERMETHOD_DESC', 'Method used to deliver email. Default is "mail()", use others only if that makes trouble.');
define('_MI_PHPMAILER_CONF_SMTP_USE_TLS', 'Use TLS if supported');
define('_MI_PHPMAILER_CONF_SMTP_USE_TLS_DESC', 'Try to use TLS if server advertises it is available.');
define('_MI_PHPMAILER_CONF_SMTPDEBUG', 'Enable SMTP debugging');
define('_MI_PHPMAILER_CONF_SMTPDEBUG_DESC', 'Log extra output from SMTP');

define('_MI_PHPMAILER_CONF_SENDMAILPATH', 'Path to sendmail');
define('_MI_PHPMAILER_CONF_SENDMAILPATH_DESC', 'Path to the sendmail program (or substitute) on the webserver.');
