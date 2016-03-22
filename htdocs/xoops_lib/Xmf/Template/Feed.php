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
 * @copyright 2011-2016 XOOPS Project (http://xoops.org)
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
    private $title = '';

    /**
     * @var string
     */
    private $url = '';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $language = '';

    /**
     * @var string
     */
    private $charset = '';

    /**
     * @var string
     */
    private $category = '';

    /**
     * @var string
     */
    private $pubdate = '';

    /**
     * @var string
     */
    private $webmaster = '';

    /**
     * @var string
     */
    private $generator = '';

    /**
     * @var string
     */
    private $copyright = '';

    /**
     * @var string
     */
    private $lastbuild = '';

    /**
     * @var string
     */
    private $editor = '';

    /**
     * @var int
     */
    private $ttl = 60;

    /**
     * @var string
     */
    private $image_title = '';

    /**
     * @var string
     */
    private $image_url = '';

    /**
     * @var string
     */
    private $image_link = '';

    /**
     * @var int
     */
    private $image_width = 200;

    /**
     * @var int
     */
    private $image_height = 50;

    /**
     * @var array
     */
    private $items = array();

    /**
     * init - called by parent::_construct
     *
     * @return void
     */
    protected function init()
    {
        $this->setTemplate('module:xmf/xmf_feed.tpl');
        //$this->disableLogger();

        global $xoopsConfig;
        $this->title = $xoopsConfig['sitename'];
        $this->url = \XoopsBaseConfig::get('url');
        $this->description = $xoopsConfig['slogan'];
        $this->language = \XoopsLocale::getLangCode();
        $this->charset = \XoopsLocale::getCharset();
        $this->pubdate = \XoopsLocale::formatTimestamp(time(), 'short');
        $this->lastbuild = \XoopsLocale::formatTimestamp(time(), 'D, d M Y H:i:s');
        $this->webmaster = $xoopsConfig['adminmail'];
        $this->editor = $xoopsConfig['adminmail'];
        $this->generator = \Xoops::VERSION;
        $this->copyright = 'Copyright ' . \XoopsLocale::formatTimestamp(time(), 'Y') . ' ' . $xoopsConfig['sitename'];
        $this->image_title = $this->title;
        $this->image_url = \XoopsBaseConfig::get('url') . '/images/logo.gif';
        $this->image_link = $this->url;
    }

    /**
     * Render the feed and display it directly
     *
     * @return void
     */
    protected function render()
    {
        $this->tpl->assign('channel_charset', $this->charset);
        $this->tpl->assign('channel_title', $this->title);
        $this->tpl->assign('channel_link', $this->url);
        $this->tpl->assign('channel_desc', $this->description);
        $this->tpl->assign('channel_webmaster', $this->webmaster);
        $this->tpl->assign('channel_editor', $this->editor);
        $this->tpl->assign('channel_category', $this->category);
        $this->tpl->assign('channel_generator', $this->generator);
        $this->tpl->assign('channel_language', $this->language);
        $this->tpl->assign('channel_lastbuild', $this->lastbuild);
        $this->tpl->assign('channel_copyright', $this->copyright);
        $this->tpl->assign('channel_ttl', $this->ttl);
        $this->tpl->assign('channel_image_url', $this->image_url);
        $this->tpl->assign('channel_image_title', $this->image_title);
        $this->tpl->assign('channel_image_url', $this->image_url);
        $this->tpl->assign('channel_image_link', $this->image_link);
        $this->tpl->assign('channel_image_width', $this->image_width);
        $this->tpl->assign('channel_image_height', $this->image_height);
        $this->tpl->assign('channel_items', $this->items);
    }

    /**
     * setCategory
     *
     * @param string $category feed category
     *
     * @return Feed
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * getCategory
     *
     * @return string feed category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * setCharset
     *
     * @param string $charset feed character set
     *
     * @return Feed
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * getCharset
     *
     * @return string feed character set
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * setCopyright
     *
     * @param string $copyright feed copyright
     *
     * @return Feed
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
        return $this;
    }

    /**
     * getCopyright
     *
     * @return string feed copyright
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * setDescription
     *
     * @param string $description feed description
     *
     * @return Feed
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * getDescription
     *
     * @return string feed description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * setEditor
     *
     * @param string $editor editor of feed
     *
     * @return Feed
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;
        return $this;
    }

    /**
     * getEditor
     *
     * @return string editor of feed
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * setGenerator
     *
     * @param string $generator feed generator
     *
     * @return Feed
     */
    public function setGenerator($generator)
    {
        $this->generator = $generator;
        return $this;
    }

    /**
     * getGenerator
     *
     * @return string feed generator
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * setImageHeight
     *
     * @param int $image_height height of feed image
     *
     * @return Feed
     */
    public function setImageHeight($image_height)
    {
        $this->image_height = $image_height;
        return $this;
    }

    /**
     * getImageHeight
     *
     * @return int height of feed image
     */
    public function getImageHeight()
    {
        return $this->image_height;
    }

    /**
     * setImageLink
     *
     * @param string $image_link feed image link
     *
     * @return Feed
     */
    public function setImageLink($image_link)
    {
        $this->image_link = $image_link;
        return $this;
    }

    /**
     * getImageLink
     *
     * @return string feed image link
     */
    public function getImageLink()
    {
        return $this->image_link;
    }

    /**
     * setImageTitle
     *
     * @param string $image_title feed image title
     *
     * @return Feed
     */
    public function setImageTitle($image_title)
    {
        $this->image_title = $image_title;
        return $this;
    }

    /**
     * getImageTitle
     *
     * @return string feed image title
     */
    public function getImageTitle()
    {
        return $this->image_title;
    }

    /**
     * setImageUrl
     *
     * @param string $image_url url of feed image
     *
     * @return Feed
     */
    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
        return $this;
    }

    /**
     * getImageUrl
     *
     * @return string url of feed image
     */
    public function getImageUrl()
    {
        return $this->image_url;
    }

    /**
     * setImageWidth
     *
     * @param int $image_width width of feed image
     *
     * @return Feed
     */
    public function setImageWidth($image_width)
    {
        $this->image_width = $image_width;
        return $this;
    }

    /**
     * getImageWidth
     *
     * @return int width of feed image
     */
    public function getImageWidth()
    {
        return $this->image_width;
    }

    /**
     * setItems
     *
     * @param array $items feed items
     *
     * @return Feed
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * getItems
     *
     * @return array feed items
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * setLanguage
     *
     * @param string $language feed language
     *
     * @return Feed
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * getLanguage
     *
     * @return string feed language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * setLastbuild
     *
     * @param string $lastbuild last build time
     *
     * @return Feed
     */
    public function setLastbuild($lastbuild)
    {
        $this->lastbuild = $lastbuild;
        return $this;
    }

    /**
     * getLastbuild
     *
     * @return string last build time
     */
    public function getLastbuild()
    {
        return $this->lastbuild;
    }

    /**
     * setPubdate
     *
     * @param string $pubdate publish date
     *
     * @return Feed
     */
    public function setPubdate($pubdate)
    {
        $this->pubdate = $pubdate;
        return $this;
    }

    /**
     * getPubdate
     *
     * @return string publish date
     */
    public function getPubdate()
    {
        return $this->pubdate;
    }

    /**
     * setTitle
     *
     * @param string $title feed title
     *
     * @return Feed
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * getTitle
     *
     * @return string feed title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * setTtl
     *
     * @param int $ttl feed time to live
     *
     * @return Feed
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }

    /**
     * getTtl
     *
     * @return int feed time to live
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * set url
     *
     * @param string $url feed site url
     *
     * @return Feed
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * getUrl
     *
     * @return string feed site url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * setWebmaster
     *
     * @param string $webmaster feed site webmaster
     *
     * @return Feed
     */
    public function setWebmaster($webmaster)
    {
        $this->webmaster = $webmaster;
        return $this;
    }

    /**
     * getWebmaster
     *
     * @return string feed site webmaster
     */
    public function getWebmaster()
    {
        return $this->webmaster;
    }
}
