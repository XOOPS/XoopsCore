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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xml
 * @since           1.0.0
 * @author          Kazumi Ono (AKA onokazu)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XoopsThemeSetParser extends SaxParser
{
    /**
     * @var array
     */
    public $tempArr = array();

    /**
     * @var array
     */
    public $themeSetData = array();

    /**
     * @var array
     */
    public $imagesData = array();

    /**
     * @var array
     */
    public $templatesData = array();

    /**
     * @param string $input
     */
    public function __construct(&$input)
    {
        parent::__construct($input);
        $this->addTagHandler(new ThemeSetDateCreatedHandler());
        $this->addTagHandler(new ThemeSetAuthorHandler());
        $this->addTagHandler(new ThemeSetDescriptionHandler());
        $this->addTagHandler(new ThemeSetGeneratorHandler());
        $this->addTagHandler(new ThemeSetNameHandler());
        $this->addTagHandler(new ThemeSetEmailHandler());
        $this->addTagHandler(new ThemeSetLinkHandler());
        $this->addTagHandler(new ThemeSetTemplateHandler());
        $this->addTagHandler(new ThemeSetImageHandler());
        $this->addTagHandler(new ThemeSetModuleHandler());
        $this->addTagHandler(new ThemeSetFileTypeHandler());
        $this->addTagHandler(new ThemeSetTagHandler());
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setThemeSetData($name, &$value)
    {
        $this->themeSetData[$name] = $value;
    }

    /**
     * @param string $name
     * @return array|bool
     */
    public function getThemeSetData($name = null)
    {
        if (isset($name)) {
            if (isset($this->themeSetData[$name])) {
                return $this->themeSetData[$name];
            }
            return false;
        }
        return $this->themeSetData;
    }

    /**
     * @param array $imagearr
     * @return void
     */
    public function setImagesData(&$imagearr)
    {
        $this->imagesData[] = $imagearr;
    }

    /**
     * @return array
     */
    public function getImagesData()
    {
        return $this->imagesData;
    }


    /**
     * @param array $tplarr
     * @return void
     */
    public function setTemplatesData(&$tplarr)
    {
        $this->templatesData[] = $tplarr;
    }

    /**
     * @return array
     */
    public function getTemplatesData()
    {
        return $this->templatesData;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $delim
     * @return void
     */
    public function setTempArr($name, &$value, $delim = '')
    {
        if (!isset($this->tempArr[$name])) {
            $this->tempArr[$name] = $value;
        } else {
            $this->tempArr[$name] .= $delim . $value;
        }
    }

    /**
     * @return array
     */
    public function getTempArr($name = null)
    {
        if (isset($name)) {
            if (isset($this->tempArr[$name])) {
                return $this->tempArr[$name];
            }
            return false;
        }
        return $this->tempArr;
    }

    /**
     * @return void
     */
    public function resetTempArr()
    {
        unset($this->tempArr);
        $this->tempArr = array();
    }
}

class ThemeSetDateCreatedHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'dateCreated';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
		if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'themeset':
                $parser->setThemeSetData('date', $data);
                break;
            default:
                break;
        }
    }
}

class ThemeSetAuthorHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'author';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(XoopsXmlRpcParser &$parser, &$attributes)
    {
        if ($parser) $parser->resetTempArr();
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @return void
     */
    public function handleEndElement(XoopsXmlRpcParser &$parser)
    {
        //todo where does this method come from??
        if ($parser) $parser->setCreditsData($parser->getTempArr());
    }
}

class ThemeSetDescriptionHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'description';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
		if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'template':
                $parser->setTempArr('description', $data);
                break;
            case 'image':
                $parser->setTempArr('description', $data);
                break;
            default:
                break;
        }
    }
}

class ThemeSetGeneratorHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'generator';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
		if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'themeset':
                $parser->setThemeSetData('generator', $data);
                break;
            default:
                break;
        }
    }
}

class ThemeSetNameHandler extends XmlTagHandler
{

    public function getName()
    {
        return 'name';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
		if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'themeset':
                $parser->setThemeSetData('name', $data);
                break;
            case 'author':
                $parser->setTempArr('name', $data);
                break;
            default:
                break;
        }
    }
}

class ThemeSetEmailHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'email';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
		if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'author':
                $parser->setTempArr('email', $data);
                break;
            default:
                break;
        }
    }
}

class ThemeSetLinkHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'link';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
		if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'author':
                $parser->setTempArr('link', $data);
                break;
            default:
                break;
        }
    }
}

class ThemeSetTemplateHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'template';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(XoopsXmlRpcParser &$parser, &$attributes)
    {
		if (!$parser) return;
        $parser->resetTempArr();
        $parser->setTempArr('name', $attributes['name']);
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @return void
     */
    public function handleEndElement(XoopsXmlRpcParser &$parser)
    {
		if (!$parser) return;
        $parser->setTemplatesData($parser->getTempArr());
    }
}

class ThemeSetImageHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'image';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(XoopsXmlRpcParser &$parser, &$attributes)
    {
		if (!$parser) return;
        $parser->resetTempArr();
        $parser->setTempArr('name', $attributes[0]);
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @return void
     */
    public function handleEndElement(XoopsXmlRpcParser &$parser)
    {
		if (!$parser) return;
        $parser->setImagesData($parser->getTempArr());
    }
}

class ThemeSetModuleHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'module';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
		if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'template':
            case 'image':
                $parser->setTempArr('module', $data);
                break;
            default:
                break;
        }
    }
}

class ThemeSetFileTypeHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'fileType';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
		if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'template':
                $parser->setTempArr('type', $data);
                break;
            default:
                break;
        }
    }
}

class ThemeSetTagHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'tag';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
		if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'image':
                $parser->setTempArr('tag', $data);
                break;
            default:
                break;
        }
    }
}