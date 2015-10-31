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
 * CAPTCHA class For XOOPS
 *
 * PHP 5.3
 *
 * @category  Xoops\Class\Captcha\CaptchaImageClass
 * @package   CaptchaImageClass
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @author    redheadedrod <redheadedrod@hotmail.com>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   $Id$
 * @link      http://xoops.org
 * @since     2.6.0
 */
class XoopsCaptchaImageHandler
{
    /**
     * @var array
     */
    public $config = array();
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $mode = 'gd';

    /**
     * @var bool
     */
    public $invalid = false;

    /**
     * @var string
     */
    public $font;

    /**
     * @var int
     */
    public $spacing;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @var XoopsCaptcha
     */
    public $captcha_handler;

    /**
     * @var object
     */
    public $oImage;

    /**
     * @var string
     */
    protected $xoops_root_path;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->captcha_handler = XoopsCaptcha::getInstance();
        $this->config = $this->captcha_handler->loadConfig("image");
        $this->xoops_root_path = \XoopsBaseConfig::get('root-path');
    }

    /**
     * load an image
     *
     * @return void
     */
    public function loadImage()
    {
        $this->generateCode();
        $this->createImage();
    }

    /**
     * Create Code
     *
     * @return bool
     */
    public function generateCode()
    {
        if ($this->invalid) {
            return false;
        }

        if ($this->mode == "bmp") {
            $this->config["num_chars"] = 4;
            $this->code = mt_rand(pow(10, $this->config["num_chars"] - 1), (int)(str_pad("9", $this->config["num_chars"], "9")));
        } else {
            $raw_code = md5(uniqid(mt_rand(), 1));
            if (!empty($this->config["skip_characters"])) {
                $valid_code = str_replace($this->config["skip_characters"], "", $raw_code);
                $this->code = substr($valid_code, 0, $this->config["num_chars"]);
            } else {
                $this->code = substr($raw_code, 0, $this->config["num_chars"]);
            }
            if (!$this->config["casesensitive"]) {
                $this->code = strtoupper($this->code);
            }
        }
        $this->captcha_handler->setCode($this->code);
        return true;
    }

    /**
     * create an image
     *
     * @return bool|string|void
     */
    public function createImage()
    {
        if ($this->invalid) {
            header("Content-type: image/gif");
            readfile($this->xoops_root_path . "/images/subject/icon2.gif");
            return false;
        }

        if ($this->mode == "bmp") {
            return $this->createImageBmp();
        } else {
            return $this->createImageGd();
        }
    }

    /**
     * Get a list of extensions
     *
     * @param string $name      name of captcha looking for
     * @param string $extension extentions for captcha
     *
     * @return array|mixed
     */
    public function getList($name, $extension = "")
    {
        if ($items = \Xoops\Cache::read("captcha_captcha_{$name}")) {
            return $items;
        }
        ;
        $file_path = $this->xoops_root_path . "/class/captcha/image/{$name}";
        $files = \Xoops\Core\Lists\File::getList($file_path);
        foreach ($files as $item) {
            if (empty($extension) || preg_match("/(\.{$extension})$/i", $item)) {
                $items[] = $item;
            }
        }
        \Xoops\Cache::write("captcha_captcha_{$name}", $items);
        return $items;
    }

    /**
     *  Create CAPTCHA iamge with GD
     *
     *  Originated by DuGris' SecurityImage
     *
     * @copyright       XOOPS Project (http://xoops.org)
     * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
     * @author          DuGris aka L. Jen <http://www.dugris.info> <DuGris@wanadoo.fr>
     * @version         $Id$
     *
     * @return void
     */
    public function createImageGd()
    {
        $this->loadFont();
        $this->setImageSize();

        $this->oImage = imagecreatetruecolor($this->width, $this->height);
        $background = imagecolorallocate($this->oImage, 255, 255, 255);
        imagefilledrectangle($this->oImage, 0, 0, $this->width, $this->height, $background);
        switch ($this->config["background_type"]) {
            case 0:
                $this->drawBars();
                break;
            case 1:
                $this->drawCircles();
                break;
            case 2:
                $this->drawLines();
                break;
            case 3:
                $this->drawRectangles();
                break;
            case 4:
                $this->drawEllipses();
                break;
            case 5:
                $this->drawPolygons();
                break;
            case 100:
                $this->createFromFile();
                break;
            default:
        }
        $this->drawBorder();
        $this->drawCode();

        header("Content-type: image/jpeg");
        imagejpeg($this->oImage);
        imagedestroy($this->oImage);
    }

    /**
     * loads a font
     *
     * @return void
     */
    public function loadFont()
    {
        $fonts = $this->getList("fonts", "ttf");
        $this->font = $this->xoops_root_path . "/class/captcha/image/fonts/" . $fonts[array_rand($fonts)];
    }

    /**
     * sets the size of the image
     *
     * @return void
     */
    public function setImageSize()
    {
        if (empty($this->font)) {
            $this->loadFont();
        }
        $MaxCharWidth = 0;
        $MaxCharHeight = 0;
        $oImage = imagecreatetruecolor(100, 100);
        $FontSize = $this->config["fontsize_max"];
        for ($Angle = -30; $Angle <= 30; ++$Angle) {
            for ($i = 65; $i <= 90; ++$i) {
                $CharDetails = imageftbbox($FontSize, $Angle, $this->font, chr($i), array());
                $_MaxCharWidth = abs($CharDetails[0] + $CharDetails[2]);
                if ($_MaxCharWidth > $MaxCharWidth) {
                    $MaxCharWidth = $_MaxCharWidth;
                }
                $_MaxCharHeight = abs($CharDetails[1] + $CharDetails[5]);
                if ($_MaxCharHeight > $MaxCharHeight) {
                    $MaxCharHeight = $_MaxCharHeight;
                }
            }
        }
        imagedestroy($oImage);

        $this->height = $MaxCharHeight + 2;
        $this->spacing = (int)(($this->config["num_chars"] * $MaxCharWidth) / $this->config["num_chars"]);
        $this->width = (int)(($this->config["num_chars"] * $MaxCharWidth) + ($this->spacing / 2));
    }

    /**
     * Return random background
     *
     * @return string|null
     */
    public function loadBackground()
    {
        $RandBackground = null;
        if ($backgrounds = $this->getList("backgrounds", "(gif|jpg|png)")) {
            $RandBackground = $this->xoops_root_path . "/class/captcha/image/backgrounds/" . $backgrounds[array_rand($backgrounds)];
        }
        return $RandBackground;
    }

    /**
     * Draw Image background
     *
     * @return void
     */
    public function createFromFile()
    {
        if ($RandImage = $this->loadBackground()) {
            $ImageType = @getimagesize($RandImage);
            switch (@$ImageType[2]) {
            case 1:
                $BackgroundImage = imagecreatefromgif($RandImage);
                break;
            case 2:
                $BackgroundImage = imagecreatefromjpeg($RandImage);
                break;
            case 3:
                $BackgroundImage = imagecreatefrompng($RandImage);
                break;
            }
        }
        if (isset($BackgroundImage) && !empty($BackgroundImage)) {
            imagecopyresized($this->oImage, $BackgroundImage, 0, 0, 0, 0, imagesx($this->oImage), imagesy($this->oImage), imagesx($BackgroundImage), imagesy($BackgroundImage));
            imagedestroy($BackgroundImage);
        } else {
            $this->drawBars();
        }
    }

    /**
     * Draw Code
     *
     * @return void
     */
    public function drawCode()
    {
        for ($i = 0; $i < $this->config["num_chars"]; ++$i) {
            // select random greyscale colour
            $text_color = imagecolorallocate($this->oImage, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));

            // write text to image
            $Angle = mt_rand(10, 30);
            if (($i % 2)) {
                $Angle = mt_rand(-30, -10);
            }

            // select random font size
            $FontSize = mt_rand($this->config["fontsize_min"], $this->config["fontsize_max"]);

            $CharDetails = imageftbbox($FontSize, $Angle, $this->font, $this->code[$i], array());
            $CharHeight = abs($CharDetails[1] + $CharDetails[5]);

            // calculate character starting coordinates
            $posX = ($this->spacing / 2) + ($i * $this->spacing);
            $posY = 2 + ($this->height / 2) + ($CharHeight / 4);

            imagefttext(
                $this->oImage, $FontSize, $Angle, $posX, $posY, $text_color, $this->font, $this->code[$i], array()
            );
        }
    }

    /**
     * Draw Border
     *
     * @return void
     */
    public function drawBorder()
    {
        $rgb = mt_rand(50, 150);
        $border_color = imagecolorallocate($this->oImage, $rgb, $rgb, $rgb);
        imagerectangle($this->oImage, 0, 0, $this->width - 1, $this->height - 1, $border_color);
    }

    /**
     * Draw Circles background
     *
     * @return void
     */
    public function drawCircles()
    {
        for ($i = 1; $i <= $this->config["background_num"]; ++$i) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imagefilledellipse($this->oImage, mt_rand(0, $this->width - 10), mt_rand(0, $this->height - 3), mt_rand(10, 20), mt_rand(20, 30), $randomcolor);
        }
    }

    /**
     * Draw Lines background
     *
     * @return void
     */
    public function drawLines()
    {
        for ($i = 0; $i < $this->config["background_num"]; ++$i) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imageline($this->oImage, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $randomcolor);
        }
    }

    /**
     * Draw Rectangles background
     *
     * @return void
     */
    public function drawRectangles()
    {
        for ($i = 1; $i <= $this->config["background_num"]; ++$i) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imagefilledrectangle($this->oImage, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $randomcolor);
        }
    }

    /**
     * Draw Bars background
     *
     * @return void
     */
    public function drawBars()
    {
        for ($i = 0; $i <= $this->height;) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imageline($this->oImage, 0, $i, $this->width, $i, $randomcolor);
            $i = $i + 2.5;
        }
        for ($i = 0; $i <= $this->width;) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imageline($this->oImage, $i, 0, $i, $this->height, $randomcolor);
            $i = $i + 2.5;
        }
    }

    /**
     * Draw Ellipses background
     *
     * @return void
     */
    public function drawEllipses()
    {
        for ($i = 1; $i <= $this->config["background_num"]; ++$i) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imageellipse($this->oImage, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $randomcolor);
        }
    }

    /**
     * Draw polygons background
     *
     * @return voif
     */
    public function drawPolygons()
    {
        for ($i = 1; $i <= $this->config["background_num"]; ++$i) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            $coords = array();
            for ($j = 1; $j <= $this->config["polygon_point"]; ++$j) {
                $coords[] = mt_rand(0, $this->width);
                $coords[] = mt_rand(0, $this->height);
            }
            imagefilledpolygon($this->oImage, $coords, $this->config["polygon_point"], $randomcolor);
        }
    }

    /**#@-*/

    /**
     * Create CAPTCHA image with BMP
     *
     * @param string $file filename
     *
     * @return string of image
     */
    public function createImageBmp($file = "")
    {
        $image = "";

        if (empty($file)) {
            header("Content-type: image/bmp");
            echo $image;
            exit();
        } else {
            return $image;
        }
    }
}
