<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Template;

/**
 * Feed implements a basic rss feed
 *
 * @category  Xmf\Template\Feed
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    The SmartFactory <www.smartfactory.ca>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Feed extends AbstractTemplate
{
    /**
     * @var string
     */
    private $_title = '';

    /**
     * @var string
     */
    private $_url = '';

    /**
     * @var string
     */
    private $_description = '';

    /**
     * @var string
     */
    private $_language = '';

    /**
     * @var string
     */
    private $_charset = '';

    /**
     * @var string
     */
    private $_category = '';

    /**
     * @var string
     */
    private $_pubdate = '';

    /**
     * @var string
     */
    private $_webmaster = '';

    /**
     * @var string
     */
    private $_generator = '';

    /**
     * @var string
     */
    private $_copyright = '';

    /**
     * @var string
     */
    private $_lastbuild = '';

    /**
     * @var string
     */
    private $_editor = '';

    /**
     * @var int
     */
    private $_ttl = 60;

    /**
     * @var string
     */
    private $_image_title = '';

    /**
     * @var string
     */
    private $_image_url = '';

    /**
     * @var string
     */
    private $_image_link = '';

    /**
     * @var int
     */
    private $_image_width = 200;

    /**
     * @var int
     */
    private $_image_height = 50;

    /**
     * @var array
     */
    private $_items = array();

    /**
     * init - called by parent::_construct
     * 
     * @return void
     */
    protected function init()
    {
        $this->setTemplate(XMF_ROOT_PATH . '/templates/xmf_feed.html');
        $this->disableLogger();

        global $xoopsConfig;
        $this->_title = $xoopsConfig['sitename'];
        $this->_url = XOOPS_URL;
        $this->_description = $xoopsConfig['slogan'];
        $this->_language = _LANGCODE;
        $this->_charset = _CHARSET;
        $this->_pubdate = date(_DATESTRING, time());
        $this->_lastbuild = formatTimestamp(time(), 'D, d M Y H:i:s');
        $this->_webmaster = $xoopsConfig['adminmail'];
        $this->_editor = $xoopsConfig['adminmail'];
        $this->_generator = XOOPS_VERSION;
        $this->_copyright = 'Copyright ' . formatTimestamp(time(), 'Y') . ' ' . $xoopsConfig['sitename'];
        $this->_image_title = $this->_title;
        $this->_image_url = XOOPS_URL . '/images/logo.gif';
        $this->_image_link = $this->_url;
    }

    /**
     * Render the feed and display it directly
     *
     * @return void
     */
    protected function render()
    {
        $this->tpl->assign('channel_charset', $this->_charset);
        $this->tpl->assign('channel_title', $this->_title);
        $this->tpl->assign('channel_link', $this->_url);
        $this->tpl->assign('channel_desc', $this->_description);
        $this->tpl->assign('channel_webmaster', $this->_webmaster);
        $this->tpl->assign('channel_editor', $this->_editor);
        $this->tpl->assign('channel_category', $this->_category);
        $this->tpl->assign('channel_generator', $this->_generator);
        $this->tpl->assign('channel_language', $this->_language);
        $this->tpl->assign('channel_lastbuild', $this->_lastbuild);
        $this->tpl->assign('channel_copyright', $this->_copyright);
        $this->tpl->assign('channel_ttl', $this->_ttl);
        $this->tpl->assign('channel_image_url', $this->_image_url);
        $this->tpl->assign('channel_image_title', $this->_image_title);
        $this->tpl->assign('channel_image_url', $this->_image_url);
        $this->tpl->assign('channel_image_link', $this->_image_link);
        $this->tpl->assign('channel_image_width', $this->_image_width);
        $this->tpl->assign('channel_image_height', $this->_image_height);
        $this->tpl->assign('channel_items', $this->_items);
    }

    /**
     * setCategory
     * 
     * @param string $category feed category
     * 
     * @return void
     */
    public function setCategory($category)
    {
        $this->_category = $category;
    }

    /**
     * getCategory
     * 
     * @return string feed category
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * setCharset
     * 
     * @param string $charset feed character set
     * 
     * @return void
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;
    }

    /**
     * getCharset
     * 
     * @return string feed character set
     */
    public function getCharset()
    {
        return $this->_charset;
    }

    /**
     * setCopyright
     * 
     * @param string $copyright feed copyright
     * 
     * @return void
     */
    public function setCopyright($copyright)
    {
        $this->_copyright = $copyright;
    }

    /**
     * getCopyright
     * 
     * @return string feed copyright
     */
    public function getCopyright()
    {
        return $this->_copyright;
    }

    /**
     * setDescription
     * 
     * @param string $description feed description
     * 
     * @return void
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * getDescription
     * 
     * @return string feed description
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * setEditor
     * 
     * @param string $editor editor of feed
     * 
     * @return void
     */
    public function setEditor($editor)
    {
        $this->_editor = $editor;
    }

    /**
     * getEditor
     * 
     * @return string editor of feed
     */
    public function getEditor()
    {
        return $this->_editor;
    }

    /**
     * setGenerator
     * 
     * @param string $generator feed generator
     * 
     * @return void
     */
    public function setGenerator($generator)
    {
        $this->_generator = $generator;
    }

    /**
     * getGenerator
     * 
     * @return string feed generator
     */
    public function getGenerator()
    {
        return $this->_generator;
    }

    /**
     * setImageHeight
     * 
     * @param int $image_height height of feed image
     * 
     * @return void
     */
    public function setImageHeight($image_height)
    {
        $this->_image_height = $image_height;
    }

    /**
     * getImageHeight
     * 
     * @return int height of feed image
     */
    public function getImageHeight()
    {
        return $this->_image_height;
    }

    /**
     * setImageLink
     * 
     * @param string $image_link feed image link
     * 
     * @return void
     */
    public function setImageLink($image_link)
    {
        $this->_image_link = $image_link;
    }

    /**
     * getImageLink
     * 
     * @return string feed image link
     */
    public function getImageLink()
    {
        return $this->_image_link;
    }

    /**
     * setImageTitle
     * 
     * @param string $image_title feed image title
     * 
     * @return void
     */
    public function setImageTitle($image_title)
    {
        $this->_image_title = $image_title;
    }

    /**
     * getImageTitle
     * 
     * @return string feed image title
     */
    public function getImageTitle()
    {
        return $this->_image_title;
    }

    /**
     * setImageUrl
     * 
     * @param string $image_url url of feed image
     * 
     * @return void
     */
    public function setImageUrl($image_url)
    {
        $this->_image_url = $image_url;
    }

    /**
     * getImageUrl
     * 
     * @return string url of feed image
     */
    public function getImageUrl()
    {
        return $this->_image_url;
    }

    /**
     * setImageWidth
     * 
     * @param int $image_width width of feed image
     * 
     * @return void
     */
    public function setImageWidth($image_width)
    {
        $this->_image_width = $image_width;
    }

    /**
     * getImageWidth
     * 
     * @return int width of feed image
     */
    public function getImageWidth()
    {
        return $this->_image_width;
    }

    /**
     * setItems
     * 
     * @param array $items feed items
     * 
     * @return void
     */
    public function setItems($items)
    {
        $this->_items = $items;
    }

    /**
     * getItems
     * 
     * @return array feed items
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * setLanguage
     * 
     * @param string $language feed language
     * 
     * @return void
     */
    public function setLanguage($language)
    {
        $this->_language = $language;
    }

    /**
     * getLanguage
     * 
     * @return string feed language
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * setLastbuild
     * 
     * @param string $lastbuild last build time
     * 
     * @return void
     */
    public function setLastbuild($lastbuild)
    {
        $this->_lastbuild = $lastbuild;
    }

    /**
     * getLastbuild
     * 
     * @return string last build time
     */
    public function getLastbuild()
    {
        return $this->_lastbuild;
    }

    /**
     * setPubdate
     * 
     * @param string $pubdate publish date
     * 
     * @return void
     */
    public function setPubdate($pubdate)
    {
        $this->_pubdate = $pubdate;
    }

    /**
     * getPubdate
     * 
     * @return string publish date
     */
    public function getPubdate()
    {
        return $this->_pubdate;
    }

    /**
     * setTitle
     * 
     * @param string $title feed title
     * 
     * @return void
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * getTitle
     * 
     * @return string feed title
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * setTtl
     * 
     * @param int $ttl feed time to live
     * 
     * @return void
     */
    public function setTtl($ttl)
    {
        $this->_ttl = $ttl;
    }

    /**
     * getTtl 
     * 
     * @return int feed time to live
     */
    public function getTtl()
    {
        return $this->_ttl;
    }

    /**
     * set url
     * 
     * @param string $url feed site url
     * 
     * @return void
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * getUrl
     * 
     * @return string feed site url
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * setWebmaster
     * 
     * @param string $webmaster feed site webmaster
     * 
     * @return void
     */
    public function setWebmaster($webmaster)
    {
        $this->_webmaster = $webmaster;
    }

    /**
     * getWebmaster
     * 
     * @return string feed site webmaster
     */
    public function getWebmaster()
    {
        return $this->_webmaster;
    }
}
