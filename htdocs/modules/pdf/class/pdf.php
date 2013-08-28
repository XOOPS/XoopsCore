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
 * Pdf
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         pdf
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

include_once dirname(dirname(__FILE__)) . '/html2pdf/html2pdf.class.php';

class Pdf extends HTML2PDF
{
    /**
     * class constructor
     *
     * @access public
     *
     * @param  string   $orientation page orientation, same as TCPDF
     * @param  mixed    $format      The format used for pages, same as TCPDF
     * @param  string   $langue      Langue : fr, en, it...
     * @param  boolean  $unicode     TRUE means that the input text is unicode (default = true)
     * @param  string   $encoding    charset encoding; default is UTF-8
     * @param  array    $marges      Default marges (left, top, right, bottom)
     *
     * @return Pdf $this
     */
    public function __construct($orientation = 'P', $format = 'A4', $langue = '', $unicode = true, $encoding = '', $marges = array(
        5, 5, 5, 8
    ))
    {
        $encoding = empty($encoding) ? XoopsLocale::getCharset() : $encoding;
        $langue = empty($langue) ? XoopsLocale::getLangCode() : $langue;
        $xoops = Xoops::getInstance();
        $xoops->disableErrorReporting();

        return parent::__construct($orientation, $format, $langue, $unicode, $encoding, $marges = array(5, 5, 5, 8));
    }

    /**
     *  Destructor
     */
    public function __destruct()
    {
        parent::__destruct();
    }
}