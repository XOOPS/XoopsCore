<?php

class XoopsXmlRss2Parser extends SaxParser
{
    /**
     * @var array
     */
    private $_tempArr = array();

    /**
     * @var array
     */
    private $_channelData = array();

    /**
     * @var array
     */
    private $_imageData = array();

    /**
     * @var array
     */
    private $_items = array();

    public function __construct(&$input)
    {
    }

    public function setChannelData($name, &$value)
    {
    }

    public function getChannelData($name = null)
    {
    }

    public function setImageData($name, &$value)
    {
    }

    public function getImageData($name = null)
    {
    }

    public function setItems(&$itemarr)
    {
    }

    public function getItems()
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

class RssChannelHandler extends XmlTagHandler
{
    public function getName()
    {
    }
}

class RssTitleHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssLinkHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssDescriptionHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssGeneratorHandler extends XmlTagHandler
{
    public function getName()
    {
    }
	
    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssCopyrightHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssNameHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssManagingEditorHandler extends XmlTagHandler
{

    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssLanguageHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssWebMasterHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssDocsHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssTtlHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssTextInputHandler extends XmlTagHandler
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

class RssLastBuildDateHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssImageHandler extends XmlTagHandler
{
    public function getName()
    {
    }
}

class RssUrlHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssWidthHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssHeightHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssItemHandler extends XmlTagHandler
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

class RssCategoryHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssCommentsHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssPubDateHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssGuidHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssAuthorHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}

class RssSourceHandler extends XmlTagHandler
{
    public function getName()
    {
    }

    public function handleBeginElement(&$parser, &$attributes)
    {
    }

    public function handleCharacterData(&$parser, &$data)
    {
    }
}