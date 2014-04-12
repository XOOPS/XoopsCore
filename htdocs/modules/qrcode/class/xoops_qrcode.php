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
 * QRCode
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @since           2.6.0
 * @author          Laurent JEN - aka DuGris
 * @version         $Id$
 */

class _Qrcode extends Xoops\Module\Helper\HelperAbstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
        $this->setDirname('qrcode');
    }

    public function LoadConfig()
    {
        return $this->xoops()->getModuleConfigs($this->_dirname);
    }
}

include dirname(dirname(__FILE__)) . '/phpqrcode/qrlib.php';

class Xoops_qrcode extends QRcode
{
    private $outfile = false;
    private $saveandprint = false;
    public $format = 'png';
    public $eclevel = 0;
    public $size = 3;
    public $margin = 0;
    public $back_color = 0xFFFFFF;
    public $fore_color = 0x000000;

    /**
     * class constructor
     *
     * @access public
     */
    public function __construct()
    {
        XoopsLoad::addMap(array('_qrcode' => dirname(dirname(__FILE__)) . '/class/qrcode.php'));

        $xoops = Xoops::getInstance();
        $configs = _qrcode::getInstance()->LoadConfig();

        $this->setFormat();
        $this->setLevel($configs['qrcode_ecl']);
        $this->setSize($configs['qrcode_mps']);
        $this->setMargin($configs['qrcode_margin']);
        $this->setBackground($configs['qrcode_bgcolor']);
        $this->setForeground($configs['qrcode_fgcolor']);
    }

    /**
     * Render format
     */
    public function setFormat($format = 'png')
    {
        $this->format = (in_array($format, array('png', 'text', 'eps', 'svg', 'raw'))) ? $format : 'png';
    }

    /**
     * level of error correction
     * 0 - QR_ECLEVEL_L = Low
     * 1 - QR_ECLEVEL_M = Medium
     * 2 - QR_ECLEVEL_Q = Quartile
     * 3 - QR_ECLEVEL_H = High
     */
    public function setLevel($eclevel = QR_ECLEVEL_L)
    {
        $this->eclevel = (intval($eclevel)>=0 && intval($eclevel)<=3) ? intval($eclevel) : QR_ECLEVEL_L;
    }

    /**
     * define size of each of the barcode code squares measured in pixels
     * Each code square (also named "pixels" or "modules") is 4x4px.
     */
    public function setSize($size = 4)
    {
        $this->size = intval($size);
    }

    /**
     * define size of margin in pixels
     */
    public function setMargin($margin = 0)
    {
        $this->margin = intval($margin);
    }

    /**
     * Set Background color
     */
    public function setBackground($back_color = 0xFFFFFF)
    {
        $this->back_color = $back_color;
    }

    /**
     * Set Foreground color
     */
    public function setForeground($fore_color = 0x000000)
    {
        $this->fore_color = $fore_color;
    }

    /**
     * Render QRcode
     */
    public function render($text)
    {
        switch($this->format) {
            case 'text':
                parent::text($text, $this->outfile, $this->eclevel, $this->size, $this->margin, $this->saveandprint, $this->back_color, $this->fore_color);
                break;

            case 'eps':
                parent::eps($text, $this->outfile, $this->eclevel, $this->size, $this->margin, $this->saveandprint, $this->back_color, $this->fore_color);
                break;

            case 'svg':
                parent::svg($text, $this->outfile, $this->eclevel, $this->size, $this->margin, $this->saveandprint, $this->back_color, $this->fore_color);
                break;

            case 'raw':
                parent::raw($text, $this->outfile, $this->eclevel, $this->size, $this->margin, $this->saveandprint, $this->back_color, $this->fore_color);
                break;

            default:
                parent::png($text, $this->outfile, $this->eclevel, $this->size, $this->margin, $this->saveandprint, $this->back_color, $this->fore_color);
                break;
        }
    }
}
