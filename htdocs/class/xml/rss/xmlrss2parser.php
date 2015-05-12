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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xml
 * @since           1.0.0
 * @author          Kazumi Ono (AKA onokazu)
 * @version         $Id $
 */

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
        parent::__construct($input);
        $this->useUtfEncoding();
        $this->addTagHandler(new RssChannelHandler());
        $this->addTagHandler(new RssTitleHandler());
        $this->addTagHandler(new RssLinkHandler());
        $this->addTagHandler(new RssGeneratorHandler());
        $this->addTagHandler(new RssDescriptionHandler());
        $this->addTagHandler(new RssCopyrightHandler());
        $this->addTagHandler(new RssNameHandler());
        $this->addTagHandler(new RssManagingEditorHandler());
        $this->addTagHandler(new RssLanguageHandler());
        $this->addTagHandler(new RssLastBuildDateHandler());
        $this->addTagHandler(new RssWebMasterHandler());
        $this->addTagHandler(new RssImageHandler());
        $this->addTagHandler(new RssUrlHandler());
        $this->addTagHandler(new RssWidthHandler());
        $this->addTagHandler(new RssHeightHandler());
        $this->addTagHandler(new RssItemHandler());
        $this->addTagHandler(new RssCategoryHandler());
        $this->addTagHandler(new RssPubDateHandler());
        $this->addTagHandler(new RssCommentsHandler());
        $this->addTagHandler(new RssSourceHandler());
        $this->addTagHandler(new RssAuthorHandler());
        $this->addTagHandler(new RssGuidHandler());
        $this->addTagHandler(new RssTextInputHandler());
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setChannelData($name, &$value)
    {
        if (!isset($this->_channelData[$name])) {
            $this->_channelData[$name] = $value;
        } else {
            $this->_channelData[$name] .= $value;
        }
    }

    /**
     * @param string $name
     * @return array|bool
     */
    public function getChannelData($name = null)
    {
        if (isset($name)) {
            if (isset($this->_channelData[$name])) {
                return $this->_channelData[$name];
            }
            return false;
        }
        return $this->_channelData;
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setImageData($name, &$value)
    {
        $this->_imageData[$name] = $value;
    }

    /**
     * @param string $name
     * @return array|bool
     */
    public function getImageData($name = null)
    {
        if (isset($name)) {
            if (isset($this->_imageData[$name])) {
                return $this->_imageData[$name];
            }
            $return = false;
            return $return;
        }
        return $this->_imageData;
    }

    /**
     * @param array $itemarr
     * @return void
     */
    public function setItems(&$itemarr)
    {
        $this->_items[] = $itemarr;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @param string $name
     * @param array $value
     * @param string $delim
     * @return void
     */
    public function setTempArr($name, &$value, $delim = '')
    {
        if (!isset($this->_tempArr[$name])) {
            $this->_tempArr[$name] = $value;
        } else {
            $this->_tempArr[$name] .= $delim . $value;
        }
    }

    /**
     * @return array
     */
    public function getTempArr()
    {
        return $this->_tempArr;
    }

    /**
     * @return void
     */
    public function resetTempArr()
    {
        unset($this->_tempArr);
        $this->_tempArr = array();
    }
}

class RssChannelHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'channel';
    }
}

class RssTitleHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'title';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('title', $data);
                break;
            case 'image':
                $parser->setImageData('title', $data);
                break;
            case 'item':
            case 'textInput':
                $parser->setTempArr('title', $data);
                break;
            default:
                break;
        }
    }
}

class RssLinkHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'link';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('link', $data);
                break;
            case 'image':
                $parser->setImageData('link', $data);
                break;
            case 'item':
            case 'textInput':
                $parser->setTempArr('link', $data);
                break;
            default:
                break;
        }
    }
}

class RssDescriptionHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'description';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('description', $data);
                break;
            case 'image':
                $parser->setImageData('description', $data);
                break;
            case 'item':
            case 'textInput':
                $parser->setTempArr('description', $data);
                break;
            default:
                break;
        }
    }
}

class RssGeneratorHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'generator';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('generator', $data);
                break;
            default:
                break;
        }
    }
}

class RssCopyrightHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'copyright';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('copyright', $data);
                break;
            default:
                break;
        }
    }
}

class RssNameHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'name';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'textInput':
                $parser->setTempArr('name', $data);
                break;
            default:
                break;
        }
    }
}

class RssManagingEditorHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'managingEditor';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('editor', $data);
                break;
            default:
                break;
        }
    }
}

class RssLanguageHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'language';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('language', $data);
                break;
            default:
                break;
        }
    }
}

class RssWebMasterHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'webMaster';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('webmaster', $data);
                break;
            default:
                break;
        }
    }
}

class RssDocsHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'docs';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('docs', $data);
                break;
            default:
                break;
        }
    }
}

class RssTtlHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'ttl';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('ttl', $data);
                break;
            default:
                break;
        }
    }
}

class RssTextInputHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'textInput';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(&$parser, &$attributes)
    {
        $parser->resetTempArr();
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @return void
     */
    public function handleEndElement(&$parser)
    {
        $parser->setChannelData('textinput', $parser->getTempArr());
    }
}

class RssLastBuildDateHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'lastBuildDate';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('lastbuilddate', $data);
                break;
            default:
                break;
        }
    }
}

class RssImageHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'image';
    }
}

class RssUrlHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'url';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        if ($parser->getParentTag() == 'image') {
            $parser->setImageData('url', $data);
        }
    }
}

class RssWidthHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'width';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        if ($parser->getParentTag() == 'image') {
            $parser->setImageData('width', $data);
        }
    }
}

class RssHeightHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'height';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        if ($parser->getParentTag() == 'image') {
            $parser->setImageData('height', $data);
        }
    }
}

class RssItemHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'item';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(&$parser, &$attributes)
    {
        $parser->resetTempArr();
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @return void
     */
    public function handleEndElement(&$parser)
    {
        $items = $parser->getTempArr();
        $parser->setItems($items);
    }
}

class RssCategoryHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'category';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('category', $data);
                break;
            case 'item':
                $parser->setTempArr('category', $data, ', ');
                break;
            default:
                break;
        }
    }
}

class RssCommentsHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'comments';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        if ($parser->getParentTag() == 'item') {
            $parser->setTempArr('comments', $data);
        }
    }
}

class RssPubDateHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'pubDate';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        switch ($parser->getParentTag()) {
            case 'channel':
                $parser->setChannelData('pubdate', $data);
                break;
            case 'item':
                $parser->setTempArr('pubdate', $data);
                break;
            default:
                break;
        }
    }
}

class RssGuidHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'guid';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        if ($parser->getParentTag() == 'item') {
            $parser->setTempArr('guid', $data);
        }
    }
}

class RssAuthorHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'author';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        if ($parser->getParentTag() == 'item') {
            $parser->setTempArr('author', $data);
        }
    }
}

class RssSourceHandler extends XmlTagHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'source';
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(&$parser, &$attributes)
    {
        if ($parser->getParentTag() == 'item') {
            $parser->setTempArr('source_url', $attributes['url']);
        }
    }

    /**
     * @param XoopsXmlRss2Parser $parser
     * @param array $data
     * @return void
     */
    public function handleCharacterData(&$parser, &$data)
    {
        if ($parser->getParentTag() == 'item') {
            $parser->setTempArr('source', $data);
        }
    }
}
