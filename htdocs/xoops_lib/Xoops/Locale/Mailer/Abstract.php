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
 *  Xoops MailerLocal
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      Xoops Mailer Local Language
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

/**
 * Localize the mail functions
 *
 * The English localization is solely for demonstration
 */
// Do not change the class name
class Xoops_Locale_Mailer_Abstract extends XoopsMailer
{
    /**
     * Constructor
     *
     * @return Xoops_Locale_Mailer_Abstract
     */
    public function __construct()
    {
        parent::__construct();
        // It is supposed no need to change the charset
        $this->charSet = strtolower(XoopsLocale::getCharset());
        // You MUST specify the language code value so that the file exists: XOOPS_ROOT_PAT/class/mail/phpmailer/language/phpmailer.lang-["your-language-code"].php
        $this->multimailer->SetLanguage('en');
    }

    /**
     * Multibyte languages are encouraged to make their proper method for encoding FromName
     *
     * @param string $text
     * @return string
     */
    public function encodeFromName($text)
    {
        // Activate the following line if needed
        // $text = "=?{$this->charSet}?B?".base64_encode($text)."?=";
        return $text;
    }

    /**
     * Multibyte languages are encouraged to make their proper method for encoding FromName
     *
     * @param string $text
     * @return string
     */
    public function encodeSubject($text)
    {
        // Activate the following line if needed
        // $text = "=?{$this->charSet}?B?".base64_encode($text)."?=";
        return $text;
    }
}
