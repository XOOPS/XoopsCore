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
use Xoops\Core\Service\Contract\CountryflagInterface;
use Xoops\Core\Service\Response;
use Xoops\Html\Img;

/**
 * Qrcode provider for service manager
 *
 * @category  ServiceProvider
 * @package   CountryFlagProvider
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class CountryFlagProvider extends AbstractContract implements CountryflagInterface
{
    /** @var string $renderScript */
    protected $flagSource = 'media/xoops/images/flags/';

    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    public function getName()
    {
        return 'system';
    }

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Built in CountryFlag provider';
    }


    /**
     * getFlagUrl
     *
     * @param string $countryCode ISO 3166-1 alpha-2 code to select flag
     * @param string $size        'small', 'medium' or 'large'
     *
     * @return string URL to obtain Flag image for country code
     */
    private function getFlagUrl($countryCode, $size)
    {
        $size = strtolower(substr($size, 0, 1));
        $sizeDir = '64';
        switch ($size) {
            case 's':
                $sizeDir = '16';
                break;
            case 'm':
                $sizeDir = '32';
                break;
        }

        $xoops = \Xoops::getInstance();
        $flagDir = $this->flagSource . $sizeDir . '/';
        $flagFile = $flagDir . $countryCode . '.png';

        $file = $xoops->path($flagFile);
        // switch to unknown if file is not readable
        if (!is_readable($file)) {
            $flagFile = $flagDir . '_unknown.png';
        }
        $url = $xoops->url($flagFile);
        return $url;
    }

    /**
     * getImgUrl - get URL to flag based on county code
     *
     * @param Response $response    \Xoops\Core\Service\Response object
     * @param string   $countryCode ISO 3166-1 alpha-2 code to select flag
     * @param string   $size        'small', 'medium' or 'large'
     *
     * @return void  - response->value set to URL string
     */
    public function getImgUrl(Response $response, $countryCode, $size = 'large')
    {
        $response->setValue($this->getFlagUrl($countryCode, $size));
    }

    /**
     * getImgTag - get a full HTML img tag to display a flag based on county code
     *
     * @param Response $response    \Xoops\Core\Service\Response object
     * @param string   $countryCode ISO 3166-1 alpha-2 code to select flag
     * @param array    $attributes  array of attribute name => value pairs for img tag
     * @param string   $size        'small', 'medium' or 'large'
     *
     * @return void  - response->value set to image tag
     */
    public function getImgTag(
        Response $response,
        $countryCode,
        $attributes = array(),
        $size = 'large'
    ) {
        $url = $this->getFlagUrl($countryCode, $size);

        $imgTag = new Img(array('src' => $url, 'alt' => $countryCode));
        $imgTag->setAttributes($attributes);
        $response->setValue($imgTag->render());
    }
}
