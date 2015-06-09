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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xml
 * @since           1.0.0
 * @author          Kazumi Ono (AKA onokazu)
 * @version         $Id$
 */

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
     * @param SaxParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(SaxParser &$parser, &$data)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
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
     * @param SaxParser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(SaxParser &$parser, &$attributes)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
        $parser->resetTempArr();
    }

    /**
     * @param SaxParser $parser
     * @return void
     */
    public function handleEndElement(SaxParser &$parser)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
        //todo where does this method come from??
        $parser->setCreditsData($parser->getTempArr());
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
     * @param SaxParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(SaxParser &$parser, &$data)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
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
     * @param SaxParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(SaxParser &$parser, &$data)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
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
     * @param SaxParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(SaxParser &$parser, &$data)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
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
     * @param SaxParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(SaxParser &$parser, &$data)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
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
     * @param SaxParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(SaxParser &$parser, &$data)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
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
     * @param SaxParser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(SaxParser &$parser, &$attributes)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
        $parser->resetTempArr();
        if (isset($attributes['name'])) {
            $parser->setTempArr('name', $attributes['name']);
        }
    }

    /**
     * @param SaxParser $parser
     * @return void
     */
    public function handleEndElement(SaxParser &$parser)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
        $value = $parser->getTempArr();
        $parser->setTemplatesData($value);
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
     * @param SaxParser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(SaxParser &$parser, &$attributes)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
        $parser->resetTempArr();
        if (isset($attributes['name'])) {
            $parser->setTempArr('name', $attributes['name']);
        }
    }

    /**
     * @param SaxParser $parser
     * @return void
     */
    public function handleEndElement(SaxParser &$parser)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
        $value = $parser->getTempArr();
        $parser->setImagesData($value);
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
     * @param SaxParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(SaxParser &$parser, &$data)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
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
     * @param SaxParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(SaxParser &$parser, &$data)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
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
     * @param SaxParser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(SaxParser &$parser, &$data)
    {
        if (!is_a($parser,'XoopsThemeSetParser')) return;
        switch ($parser->getParentTag()) {
            case 'image':
                $parser->setTempArr('tag', $data);
                break;
            default:
                break;
        }
    }
}
