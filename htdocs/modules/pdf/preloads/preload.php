<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\PreloadItem;
use Xoops\Core\Service\Provider;

/**
 * Pdf module preloads
 *
 * @category  Pdf
 * @package   Pdf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL v2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class PdfPreload extends PreloadItem
{
    /**
     * listen for core.service.locate.htmltopdf event
     *
     * @param Provider $provider - provider object for requested service
     *
     * @return void
     */
    public static function eventCoreServiceLocateHtmlToPdf(Provider $provider)
    {
        if (is_a($provider, '\Xoops\Core\Service\Provider')) {
            $path = dirname(__DIR__) . '/class/HtmlToPdfProvider.php';
            require $path;
            $object = new HtmlToPdfProvider();
            $provider->register($object);
        }
    }
}
