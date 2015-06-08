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
use Xoops\Core\Service\Contract\QrcodeInterface;
use Xoops\Core\Service\Response;
use Xoops\Html\Img;

/**
 * Qrcode provider for service manager
 *
 * @category  ServiceProvider
 * @package   QrcodeProvider
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class QrcodeProvider extends AbstractContract implements QrcodeInterface
{
    /** @var string $renderScript */
    protected $renderScript = 'modules/qrcode/include/qrrender.php';

    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    public function getName()
    {
        return 'qrcode';
    }

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    public function getDescription()
    {
        return 'QR Code generation using endroid/qrcode';
    }


    /**
     * getQRUrl
     *
     * @param string $qrText text for QR code
     *
     * @return string URL to obtain QR Code image of $qrText
     */
    private function getQRUrl($qrText)
    {
        $xoops = \Xoops::getInstance();
        $params = array(
            'text' => (string) $qrText,
        );
        $url = $xoops->buildUrl($xoops->url($this->renderScript), $params);
        return $url;
    }

    /**
     * getImgUrl - get URL to QR Code image of supplied text
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $qrText   text to encode in QR Code
     *
     * @return void  - response->value set to URL string
     */
    public function getImgUrl(Response $response, $qrText)
    {
        $response->setValue($this->getQRUrl($qrText));
    }

    /**
     * getImgTag - get a full HTML img tag to display a QR Code image of supplied text
     *
     * @param Response $response   \Xoops\Core\Service\Response object
     * @param string   $qrText     text to encode in QR Code
     * @param array    $attributes array of attribute name => value pairs for img tag
     *
     * @return void  - response->value set to image tag
     */
    public function getImgTag(Response $response, $qrText, $attributes = array())
    {
        $url = $this->getQRUrl($qrText);

        $imgTag = new Img(array('src' => $url,));
        $imgTag->setAttributes($attributes);
        $response->setValue($imgTag->render());
    }
}
