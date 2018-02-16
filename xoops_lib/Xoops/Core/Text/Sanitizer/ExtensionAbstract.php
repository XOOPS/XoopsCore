<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Text\Sanitizer;

use Xoops\Core\Text\Sanitizer;

/**
 * XOOPS Text/Sanitizer extension
 *
 * @category  Sanitizer\ExtensionAbstract
 * @package   Xoops\Core\Text
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Goghs Cheng (http://www.eqiao.com, http://www.devbeez.com/)
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class ExtensionAbstract extends SanitizerComponent
{
    /**
     * Register extension with the supplied sanitizer instance
     *
     * was load()
     *
     * @return void
     */
    abstract public function registerExtensionProcessing();

    /**
     * Provide button and javascript code used by the DhtmlTextArea
     *
     * was encode()
     *
     * @param string $textAreaId dom element id
     *
     * @return string[] editor button as HTML, supporting javascript
     */
    public function getDhtmlEditorSupport($textAreaId)
    {
        return array('', '');
    }

    /**
     * Convenience method to create a button for the editor
     *
     * @param string $textAreaId id of element passed to onclick
     * @param string $imageName  button image
     * @param string $altText    text for alt attribute
     * @param string $onclick    javascript function should expect arguments as
     *                            ($textAreaId, $varArgs1, $varArgs2 ...)
     * @param string $varArgs    variable number of strings passed to onclick function
     *
     * @return string
     */
    protected function getEditorButtonHtml($textAreaId, $imageName, $altText, $onclick, $varArgs)
    {
        $input = func_get_args();
        $prompts = array_slice($input, 4);
        $altText = $this->ts->escapeForJavascript($altText);
        $url = \Xoops::getInstance()->url('images/form/' . $imageName);

        $buttonCode = '<img src="' . $url .'"'
            . ' alt="' . $altText .'" title="' . $altText . '"'
            . ' onclick="' . $onclick . '(\'' . $textAreaId . '\'';
        foreach ($prompts as $prompt) {
            $buttonCode .= ', \'' . $this->ts->escapeForJavascript($prompt) . '\'';
        }
        $buttonCode .= ');" />&nbsp;';

        return $buttonCode;
    }
}
