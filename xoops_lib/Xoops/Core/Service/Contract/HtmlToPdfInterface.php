<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service\Contract;

/**
 * HtmlToPdf service interface
 *
 * @category  Xoops\Core\Service\Contract\HtmlToPdfInterface
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
interface HtmlToPdfInterface
{
    const MODE = \Xoops\Core\Service\Manager::MODE_EXCLUSIVE;

    /**
     * startPdf - start a new pdf
     *
     * @param Response $response \Xoops\Core\Service\Response object
     *
     * @return void
     */
    public function startPdf($response);

    /**
     * setPageOrientation - set page orientation
     *
     * @param Response $response        \Xoops\Core\Service\Response object
     * @param string   $pageOrientation page orientation, 'P' for portrait, 'L' for landscape
     *
     * @return void
     */
    public function setPageOrientation($response, $pageOrientation);

    /**
     * setPageSize - set standard page size
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $pageSize standard named page size, i.e. 'LETTER', 'A4', etc.
     *
     * @return void
     */
    public function setPageSize($response, $pageSize);

    /**
     * setBaseUnit - set unit of measure for page size, margins, etc.
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $unit     unit used in page size, margins. Possible values include
     *                           'mm' = millimeter, "in" = inches, 'pt' = typographic points
     *
     * @return void
     */
    public function setBaseUnit($response, $unit);

    /**
     * setMargins - set margin sizes
     *
     * @param Response $response     \Xoops\Core\Service\Response object
     * @param float    $leftMargin   left margin in base units, @see setBaseUnits()
     * @param float    $topMargin    top margin in base units
     * @param float    $rightMargin  right margin in base units
     * @param float    $bottomMargin bottom margin in base units
     *
     * @return void - response->value set to absolute URL to avatar image
     */
    public function setMargins($response, $leftMargin, $topMargin, $rightMargin, $bottomMargin);

    /**
     * setBaseFont - set the base font used in rendering
     *
     * @param Response $response   \Xoops\Core\Service\Response object
     * @param string   $fontFamily font family
     * @param string   $fontStyle  font style ('bold', 'italic', etc.)
     * @param float    $fontSize   font size in points
     *
     * @return void
     */
    public function setBaseFont($response, $fontFamily, $fontStyle = '', $fontSize = null);

    /**
     * setDefaultMonospacedFont - default monotype font used in rendering
     *
     * @param Response $response       \Xoops\Core\Service\Response object
     * @param string   $monoFontFamily font family
     *
     * @return void
     */
    public function setDefaultMonospacedFont($response, $monoFontFamily);

    /**
     * setAuthor - set author in pdf meta data
     *
     * @param Response $response  \Xoops\Core\Service\Response object
     * @param string   $pdfAuthor author name
     *
     * @return void
     */
    public function setAuthor($response, $pdfAuthor);

    /**
     * setTitle - set title in pdf meta data
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $pdfTitle document title
     *
     * @return void
     */
    public function setTitle($response, $pdfTitle);

    /**
     * setSubject - set subject in pdf meta data
     *
     * @param Response $response   \Xoops\Core\Service\Response object
     * @param string   $pdfSubject document subject
     *
     * @return void
     */
    public function setSubject($response, $pdfSubject);

    /**
     * setKeywords - set keywords in pdf meta data
     *
     * @param Response $response    \Xoops\Core\Service\Response object
     * @param string[] $pdfKeywords array of keywords pertaining to document
     *
     * @return void
     */
    public function setKeywords($response, $pdfKeywords);

    /**
     * addHtml - add HTML formatted text to document. This may be called multiple times
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $html     HTML formated text to include in document
     *                           array     user info, 'uid', 'uname' and 'email' required
     *
     * @return void
     */
    public function addHtml($response, $html);

    /**
     * outputPdfInline - output a named pdf document file inline
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $name     filename for file
     *
     * @return void
     */
    public function outputPdfInline($response, $name);

    /**
     * outputPdfDownload - output a named pdf document file for download
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $name     filename for file
     *
     * @return void
     */
    public function outputPdfDownload($response, $name);

    /**
     * fetchPdf - fetch rendered document as a string
     *
     * @param Response $response \Xoops\Core\Service\Response object
     *
     * @return void - response->value set to string containing document
     */
    public function fetchPdf($response);
}
