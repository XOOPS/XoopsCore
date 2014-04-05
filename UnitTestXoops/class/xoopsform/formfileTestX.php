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
 * XOOPS form element of file
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A file upload field
 */
class XoopsFormFile extends XoopsFormElement
{
    /**
     * Maximum size for an uploaded file
     *
     * @var int
     */
    private $_maxFileSize;

    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name "name" attribute
     */
    public function __construct($caption, $name)
    {
        $this->setCaption($caption);
        $this->setName($name);

    }

    /**
     * prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        return '<input class="input-file" type="file" name="' . $this->getName() . '" id="' . $this->getName() . '" title="' . $this->getTitle() . '" ' . $this->getExtra() . ' /><input type="hidden" name="xoops_upload_file[]" id="xoops_upload_file[]" value="' . $this->getName() . '">';
    }
}