<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Service\AbstractContract;
use Xoops\Core\Service\Contract\ThumbnailInterface;
use Xoops\Core\Service\Response;
use Xoops\Html\Img;

/**
 * Thumbnail provider for service manager
 *
 * @category  ServiceProvider
 * @package   ThumbsProvider
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class ThumbsProvider extends AbstractContract implements ThumbnailInterface
{
    /** @var string $renderScript */
    protected $renderScript = 'modules/thumbs/include/thumbrender.php';

    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    public function getName()
    {
        return 'thumbs';
    }

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Thumbnail generation using stefangabos/zebra_image';
    }


    /**
     * getThumbnailUrl
     *
     * @param string  $imgPath path to image to be thumbed
     * @param integer $width   maximum width of thumbnail in pixels, 0 to use default
     * @param integer $height  maximum height of thumbnail in pixels, 0 to use default
     *
     * @return string URL to obtain QR Code image of $qrText
     */
    private function getThumbnailUrl($imgPath, $width, $height)
    {
        $xoops = \Xoops::getInstance();
        $helper  = $xoops->getModuleHelper('thumbs');
        $thumbPath = $helper->buildThumbPath($imgPath, $width, $height);

        $originalMtime = filemtime($xoops->path($imgPath));
        $thumbMtime = filemtime($xoops->path($thumbPath));
        if (false===$thumbMtime || $originalMtime>$thumbMtime) {
            $params = array(
                'img' => (string) $imgPath,
            );
            if ($height) {
                $params['h'] = $height;
            }
            if ($width) {
                $params['w'] = $width;
            }
            $url = $xoops->buildUrl($xoops->url($this->renderScript), $params);
        } else {
            $url = $xoops->url($thumbPath);
        }

        return $url;
    }

    /**
     * getImgUrl - get URL to a thumbnail of the supplied image
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $imgPath  path to image to be thumbed
     * @param integer  $width    maximum width of thumbnail in pixels, 0 to use default
     * @param integer  $height   maximum height of thumbnail in pixels, 0 to use default
     *
     * @return void  - response->value set to URL string
     */
    public function getImgUrl(Response $response, $imgPath, $width = 0, $height = 0)
    {
        $response->setValue($this->getThumbnailUrl($imgPath, $width, $height));
    }

    /**
     * getImgTag - get a full HTML img tag to display a thumbnail of the supplied image
     *
     * @param Response $response   \Xoops\Core\Service\Response object
     * @param string   $imgPath    path to image to be thumbed
     * @param integer  $width      maximum width of thumbnail in pixels, 0 to use default
     * @param integer  $height     maximum height of thumbnail in pixels, 0 to use default
     * @param array    $attributes array of attribute name => value pairs for img tag
     *
     * @return void  - response->value set to image tag
     */
    public function getImgTag(
        Response $response,
        $imgPath,
        $width = 0,
        $height = 0,
        $attributes = array()
    ) {
        $url = $this->getThumbnailUrl($imgPath, $width, $height);

        $imgTag = new Img(array('src' => $url));
        $imgTag->setAttributes($attributes);
        $response->setValue($imgTag->render());
    }
}
