<?php

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

    public function __construct(&$input)
    {
    }

    public function setThemeSetData($name, &$value)
    {
    }

    public function getThemeSetData($name = null)
    {
    }

    public function setImagesData(&$imagearr)
    {
    }

    public function getImagesData()
    {
    }

    public function setTemplatesData(&$tplarr)
    {
    }

    public function getTemplatesData()
    {
    }

    public function setTempArr($name, &$value, $delim = '')
    {
    }

    public function getTempArr()
    {
    }

    public function resetTempArr()
    {
    }
}

class ThemeSetDateCreatedHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class ThemeSetAuthorHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleBeginElement(&$parser, &$attributes)
    {
    }

    public function handleEndElement(&$parser)
    {
    }
}

class ThemeSetDescriptionHandler extends XmlTagHandler
{
    public function getName()
    {
    }
	
    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class ThemeSetGeneratorHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class ThemeSetNameHandler extends XmlTagHandler
{

    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class ThemeSetEmailHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class ThemeSetLinkHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class ThemeSetTemplateHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleBeginElement(&$parser, &$attributes)
    {
    }

    public function handleEndElement(&$parser)
    {
    }
}

class ThemeSetImageHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleBeginElement(&$parser, &$attributes)
    {
    }

    public function handleEndElement(&$parser)
    {
    }
}

class ThemeSetModuleHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class ThemeSetFileTypeHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class ThemeSetTagHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}