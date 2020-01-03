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
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
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
        $countryCode = $this->getCountryCodeOverride($countryCode);
        $size = mb_strtolower(mb_substr($size, 0, 1));
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
     * Some CLDR (Unicode Common Locale Data Repository) territory codes are not officially
     * assigned codes, exceptional reservations*, or are a disjoined territory of a county.
     * These will need to be mappped to a (hopefully) suitable flag.
     *
     * @var string[]
     */
    private $overrideMap = [
        'AC' => 'SH',       // *Ascension Island part of Saint Helena, Ascension and Tristan da Cunha
        'BQ' => 'NL',       // Caribbean Netherlands
        'BV' => 'NO',       // Bouvet Island, dependency of Norway
        'CP' => 'FR',       // *Clipperton Island,  overseas possession of France
        'DG' => 'GB',       // *Diego Garcia, British Indian Ocean Territory disputed sovereignty
        'EA' => 'ES',       // *Ceuta & Melilla, Spanish cities on the north coast of Africa
        'GF' => 'FR',       // French Guiana, overseas region of France
        'GP' => 'FR',       // Guadeloupe, overseas region of France
        'HM' => 'AU',       // Heard & McDonald Islands, Australian external territory
        'IO' => 'GB',       // British Indian Ocean Territory
        'PM' => 'FR',       // St. Pierre & Miquelon, territorial overseas collectivity of France
        'RE' => 'FR',       // Réunion, overseas region of France
        'SJ' => 'NO',       // Svalbard & Jan Mayen, integrated parts of Norway not allocated to counties
        'SX' => 'NL',       // Sint Maarten,  constituent country of the Kingdom of the Netherlands
        'TA' => 'SH',       // *Tristan da Cunha part of Saint Helena, Ascension and Tristan da Cunha
        'UM' => 'US',       // U.S. Outlying Islands
        'XK' => '_kosovo',  // (User-assigned range) temporary assigned code
    ];

    /**
     * getCountryOverride
     *
     * @param string $countryCode ISO 3166-1 alpha-2 code
     *
     * @return string possibly overridden country code
     */
    private function getCountryCodeOverride($countryCode)
    {
        $countryCode = (isset($this->overrideMap[$countryCode]))
            ? $this->overrideMap[$countryCode]
            : $countryCode;

        return $countryCode;
    }

    /**
     * getImgTag - get a full HTML tag or string to display a flag based on county code
     *
     * @param Response $response    \Xoops\Core\Service\Response object
     * @param string   $countryCode ISO 3166-1 alpha-2 code to select flag
     * @param array    $attributes  array of attribute name => value pairs for img tag
     * @param string   $size        'small', 'medium' or 'large'
     */
    public function getImgTag(
        Response $response,
        $countryCode,
        $attributes = [],
        $size = 'large'
    ) {
        $url = $this->getFlagUrl($countryCode, $size);
        if (!is_array($attributes)) {
            $attributes = [];
        }

        $imgTag = new Img(['src' => $url, 'alt' => $countryCode]);
        $imgTag->setMerge($attributes);
        $response->setValue($imgTag->render());
    }
}
