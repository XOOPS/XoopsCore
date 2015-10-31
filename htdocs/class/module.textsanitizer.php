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
 * Class to "clean up" text for various uses
 *
 * @category  Core
 * @package   Kernel
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @author    Goghs Cheng
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class MyTextSanitizer extends Xoops\Core\Text\Sanitizer
{
    /**
     * @var Sanitizer The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Sanitizer The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Add slashes to the text if magic_quotes_gpc is turned off.
     *
     * @param string $text text to not process
     *
     * @return string
     *
     * @throws LogicException
     */
    public function addSlashes($text)
    {
        throw new LogicException('GPC is dead. Please stop.');
        return $text;
    }

    /**
     * if magic_quotes_gpc is on, strip back slashes
     *
     * @param string $text text to not process
     *
     * @return string
     *
     * @throws LogicException
     */
    public function stripSlashesGPC($text)
    {
        throw new LogicException('GPC is dead. Please stop.');
        return $text;
    }

    /**
     * Filters textarea form data in DB for display
     *
     * @param string $text   string to filter
     * @param int    $html   allow html?
     * @param int    $smiley allow smileys?
     * @param int    $xcode  allow xoopscode?
     * @param int    $image  allow inline images?
     * @param int    $br     convert linebreaks?
     *
     * @return string
     */
    public function displayTarea($text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1)
    {
        return $this->filterForDisplay($text, $html, $smiley, $xcode, $image, $br);
    }

    /**
     * Filters textarea form data submitted for preview
     *
     * @param string $text   string to filter
     * @param int    $html   allow html?
     * @param int    $smiley allow smileys?
     * @param int    $xcode  allow xoopscode?
     * @param int    $image  allow inline images?
     * @param int    $br     convert linebreaks?
     *
     * @return string
     */
    public function previewTarea($text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1)
    {
        return $this->filterForDisplay($text, $html, $smiley, $xcode, $image, $br);
    }
}
