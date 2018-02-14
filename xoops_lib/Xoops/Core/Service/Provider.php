<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service;

/**
 * Service Provider object
 *
 * All provider classes should extend this class, and implement the appropriate
 * contract interface.
 *
 * @category  Xoops\Core\Service\Provider
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2015 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 *
 * @method Response getAvatarUrl(mixed $userinfo);
 * @method Response getAvatarEditUrl(\XoopsUser $userinfo);
 * @method Response getImgUrl(string $value, mixed $p2=null, mixed $d3=null, mixed $d4=null, mixed $d5=null);
 * @method Response getImgTag(string $value, mixed $d2=null, mixed $d3=null, mixed $d4=null, mixed $d5=null);
 * @method Response startPdf();
 * @method Response setPageOrientation(string $pageOrientation);
 * @method Response setPageSize(string $pageSize);
 * @method Response setBaseUnit(string $unit);
 * @method Response setMargins(float $leftMargin, float $topMargin, float $rightMargin, float $bottomMargin);
 * @method Response setBaseFont(string $fontFamily, string $fontStyle, float|null $fontSize);
 * @method Response setDefaultMonospacedFont(string $monoFontFamily);
 * @method Response setAuthor(string $pdfAuthor);
 * @method Response setTitle(string $pdfTitle);
 * @method Response setSubject(string $pdfSubject);
 * @method Response setKeywords(array $pdfKeywords);
 * @method Response addHtml(string $html);
 * @method Response outputPdfInline(string $name);
 * @method Response outputPdfDownload(string $name);
 * @method Response fetchPdf();
 * @method Response getUserRank(mixed $userinfo);
 * @method Response getAssignableUserRankList();
 * @method Response renderEmoji(string $buffer);
 * @method Response getEmojiList();
 * @method Response renderEmojiSelector(string $identifier);
 */
class Provider
{
    protected $manager = null;

    protected $service = null;

    /** @var AbstractContract[] $providers */
    protected $providers = array();

    /**
     * __construct
     *
     * @param Manager $manager Manager instance
     * @param string  $service service name (case sensitive)
     */
    public function __construct(Manager $manager, $service)
    {
        $this->manager = $manager;
        $this->service = $service;
    }

    /**
     * getProviderMode
     *
     * @return Manager MODE constant
     */
    public function getProviderMode()
    {
        static $ret = null;

        if ($ret===null) {
            if (count($this->providers)) {
                $ret = reset($this->providers)->getMode();
            } else {
                return Manager::MODE_EXCLUSIVE;
            }
        }
        return $ret;
    }

    /**
     * registerProvider - register a provider of a named service
     *
     * @param string $object instantiated object that provides the service
     *
     * @return void
     */
    public function register($object)
    {
        // verify this is the proper type of object
        $contract = '\Xoops\Core\Service\Contract\\' . $this->service . 'Interface';

        if (is_a($object, '\Xoops\Core\Service\AbstractContract')
            && $object instanceof $contract
        ) {
            $this->providers[] = $object;
        }
    }

    /**
     * getRegistered - access list of registered providers
     *
     * @return array of registered providers managed by this instance
     */
    public function &getRegistered()
    {
        return $this->providers;
    }

    /**
     * sortProviders - sort providers into priority order
     *
     * @return void
     */
    public function sortProviders()
    {
        $sortable = $this->providers;
        usort($sortable, function (AbstractContract $a, AbstractContract $b) {
            if ($a->getPriority() != $b->getPriority()) {
                return ($a->getPriority() > $b->getPriority()) ? 1 : -1;
            } else {
                return 0;
            }
        });
        $this->providers = $sortable;
    }

    /**
     * isAvailable - indicate the availability of an actual provider
     *
     * In many cases a null provider can be called without changing the flow of the calling
     * program. In some cases, the availability of a provider may need to be reflected in
     * the caller, i.e. adding a UI button or menu item.
     *
     * @return boolean true if actual provider is available, otherwise false
     */
    public function isAvailable()
    {
        return true;
    }

    /**
     * All contract specified methods go here
     *
     * @param string $name      method to call
     * @param mixed  $arguments any arguments
     *
     * @return Response Response
     */
    public function __call($name, $arguments)
    {
        $mode = $this->getProviderMode();

        // for right now only one provider will be called, and it should be at the top
        $object = reset($this->providers);
        $method = array($object, $name);
        $response = new Response();
        if (is_callable($method)) {
            try {
                //$object->$name($response, $arguments);
                array_unshift($arguments, $response);
                call_user_func_array($method, $arguments);
            } catch (\Exception $e) {
                \Xoops::getInstance()->events()->triggerEvent('core.exception', $e);
                $response->setSuccess(false)->addErrorMessage($e->getMessage());
            }
        } else {
            $response->setSuccess(false)->addErrorMessage(sprintf('No method %s', $name));
        }
        return $response;
    }

    /**
     * All static methods go here and will return null
     *
     * @param string $name      not used
     * @param mixed  $arguments not used
     *
     * @return null
     */
    public static function __callStatic($name, $arguments)
    {
        return null;
    }
}
