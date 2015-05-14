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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/include/common.php';

class PublisherMetagen
{
    /**
     * @var Publisher
     * @access public
     */
    public $publisher = null;

    /**
     * @var MyTextSanitizer
     */
    public $_myts;

    /**
     * @var string
     */
    public $_title;

    /**
     * @var string
     */
    public $_original_title;

    /**
     * @var string
     */
    public $_keywords;

    /**
     * @var string
     */
    public $_categoryPath;

    /**
     * @var string
     */
    public $_description;

    /**
     * @var int
     *
     */
    public $_minChar = 4;

    /**
     * @param string       $title
     * @param string       $keywords
     * @param string       $description
     * @param bool         $categoryPath
     */
    public function __construct($title, $keywords = '', $description = '', $categoryPath = false)
    {
        $this->publisher = Publisher::getInstance();
        $this->_myts = MyTextSanitizer::getInstance();
        $this->setCategoryPath($categoryPath);
        $this->setTitle($title);
        $this->setDescription($description);
        if ($keywords == '') {
            $keywords = $this->createMetaKeywords();
        }
        $this->setKeywords($keywords);
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = $this->html2text($title);
        $this->_original_title = $this->_title;
        $titleTag = array();
        $titleTag['module'] = $this->publisher->getModule()->getVar('name');
        if (isset($this->_title) && ($this->_title != '') && (strtoupper($this->_title) != strtoupper($titleTag['module']))) {
            $titleTag['title'] = $this->_title;
        }
        if (isset($this->_categoryPath) && ($this->_categoryPath != '')) {
            $titleTag['category'] = $this->_categoryPath;
        }
        $ret = isset($titleTag['title']) ? $titleTag['title'] : '';
        if (isset($titleTag['category']) && $titleTag['category'] != '') {
            if ($ret != '') {
                $ret .= ' - ';
            }
            $ret .= $titleTag['category'];
        }
        if (isset($titleTag['module']) && $titleTag['module'] != '') {
            if ($ret != '') {
                $ret .= ' - ';
            }
            $ret .= $titleTag['module'];
        }
        $this->_title = $ret;
    }

    /**
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->_keywords = $keywords;
    }

    /**
     * @param string $categoryPath
     */
    public function setCategoryPath($categoryPath)
    {
        $categoryPath = $this->html2text($categoryPath);
        $this->_categoryPath = $categoryPath;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $description = $this->html2text($description);
        $description = $this->purifyText($description);
        $this->_description = $description;
    }

    /**
     * Does nothing
     */
    public function createTitleTag()
    {
    }

    /**
     * @param int $maxWords
     *
     * @return string
     */
    public function createMetaDescription($maxWords = 30)
    {
        $description = $this->purifyText($this->_description);
        $description = $this->html2text($description);
        $words = explode(" ", $description);
        $ret = '';
        $i = 1;
        $wordCount = count($words);
        foreach ($words as $word) {
            $ret .= $word;
            if ($i < $wordCount) {
                $ret .= ' ';
            }
            ++$i;
        }
        return $ret;
    }

    /**
     * @param string $text
     * @param int    $minChar
     *
     * @return array
     */
    public function findMetaKeywords($text, $minChar)
    {
        $keywords = array();
        $text = $this->purifyText($text);
        $text = $this->html2text($text);
        $originalKeywords = explode(" ", $text);
        foreach ($originalKeywords as $originalKeyword) {
            $secondRoundKeywords = explode("'", $originalKeyword);
            foreach ($secondRoundKeywords as $secondRoundKeyword) {
                if (strlen($secondRoundKeyword) >= $minChar) {
                    if (!in_array($secondRoundKeyword, $keywords)) {
                        $keywords[] = trim($secondRoundKeyword);
                    }
                }
            }
        }
        return $keywords;
    }

    /**
     * @return string
     */
    public function createMetaKeywords()
    {
        $keywords = $this->findMetaKeywords($this->_original_title . " " . $this->_description, $this->_minChar);
        $moduleKeywords = $this->publisher->getConfig('seo_meta_keywords');
        if ($moduleKeywords != '') {
            $moduleKeywords = explode(",", $moduleKeywords);
            $keywords = array_merge($keywords, array_map('trim', $moduleKeywords));
        }
        $ret = implode(',', $keywords);
        return $ret;
    }

    /**
     * Does nothing
     */
    public function autoBuildMeta_keywords()
    {
    }

    /**
     * Build Metatags
     */
    public function buildAutoMetaTags()
    {
        $this->_keywords = $this->createMetaKeywords();
        $this->_description = $this->createMetaDescription();
        //$this->_title = $this->createTitleTag();
    }

    /**
     * Creates meta tags
     */
    public function createMetaTags()
    {
        global $xoopsTpl, $xoTheme;
        if ($this->_keywords != '') {
            $xoTheme->addMeta('meta', 'keywords', $this->_keywords);
        }
        if ($this->_description != '') {
            $xoTheme->addMeta('meta', 'description', $this->_description);
        }
        if ($this->_title != '') {
            $xoopsTpl->assign('xoops_pagetitle', $this->_title);
        }
    }

    /**
     * Return true if the string is length > 0
     *
     * @credit psylove
     * @var string $string Chaine de caractère
     * @return boolean
     */
    static public function emptyString($var)
    {
        return (strlen($var) > 0);
    }

    /**
     * Create a title for the short_url field of an article
     *
     * @credit psylove
     *
     * @param string $title    title of the article
     * @param bool   $withExt  do we add an html extension or not
     *
     * @return string short url for article
     */
    static  function generateSeoTitle($title = '', $withExt = true)
    {
        // Transformation de la chaine en minuscule
        // Codage de la chaine afin d'éviter les erreurs 500 en cas de caractères imprévus
        $title = rawurlencode(strtolower($title));
        // Transformation des ponctuations
        //                 Tab     Space      !        "        #        %        &        '        (        )        ,        /        :        ;        <        =        >        ?        @        [        \        ]        ^        {        |        }        ~       .
        $pattern = array("/%09/", "/%20/", "/%21/", "/%22/", "/%23/", "/%25/", "/%26/", "/%27/", "/%28/", "/%29/", "/%2C/", "/%2F/", "/%3A/", "/%3B/", "/%3C/", "/%3D/", "/%3E/", "/%3F/", "/%40/", "/%5B/", "/%5C/", "/%5D/", "/%5E/", "/%7B/", "/%7C/", "/%7D/", "/%7E/", "/\./");
        $rep_pat = array("-", "-", "-", "-", "-", "-100", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-at-", "-", "-", "-", "-", "-", "-", "-", "-", "-");
        $title = preg_replace($pattern, $rep_pat, $title);
        // Transformation des caractères accentués
        //                  °        è        é        ê        ë        ç        à        â        ä        î        ï        ù        ü        û        ô        ö
        $pattern = array("/%B0/", "/%E8/", "/%E9/", "/%EA/", "/%EB/", "/%E7/", "/%E0/", "/%E2/", "/%E4/", "/%EE/", "/%EF/", "/%F9/", "/%FC/", "/%FB/", "/%F4/", "/%F6/");
        $rep_pat = array("-", "e", "e", "e", "e", "c", "a", "a", "a", "i", "i", "u", "u", "u", "o", "o");
        $title = preg_replace($pattern, $rep_pat, $title);
        $tableau = explode("-", $title); // Transforme la chaine de caractères en tableau
        $tableau = array_filter($tableau, array('PublisherMetagen', 'emptyString')); // Supprime les chaines vides du tableau
        $title = implode("-", $tableau); // Transforme un tableau en chaine de caractères séparé par un tiret
        if (sizeof($title) > 0) {
            if ($withExt) {
                $title .= '.html';
            }
            return $title;
        }
        return '';
    }

    /**
     * @param string  $text
     * @param boolean $keyword
     *
     * @return string
     */
    public function purifyText($text, $keyword = false)
    {
        $text = str_replace('&nbsp;', ' ', $text);
        $text = str_replace('<br />', ' ', $text);
        $text = strip_tags($text);
        $text = html_entity_decode($text);
        $text = $this->_myts->undoHtmlSpecialChars($text);
        $text = str_replace(')', ' ', $text);
        $text = str_replace('(', ' ', $text);
        $text = str_replace(':', ' ', $text);
        $text = str_replace('&euro', ' euro ', $text);
        $text = str_replace('&hellip', '...', $text);
        $text = str_replace('&rsquo', ' ', $text);
        $text = str_replace('!', ' ', $text);
        $text = str_replace('?', ' ', $text);
        $text = str_replace('"', ' ', $text);
        $text = str_replace('-', ' ', $text);
        $text = str_replace('\n', ' ', $text);
        if ($keyword) {
            $text = str_replace('.', ' ', $text);
            $text = str_replace(',', ' ', $text);
            $text = str_replace('\'', ' ', $text);
        }
        $text = str_replace(';', ' ', $text);
        return $text;
    }

    /**
     * @param string $document
     *
     * @return mixed
     */
    public function html2text($document)
    {
        // PHP Manual:: function preg_replace
        // $document should contain an HTML document.
        // This will remove HTML tags, javascript sections
        // and white space. It will also convert some
        // common HTML entities to their text equivalent.
        // Credits : newbb2
        $search = array(
            "'<script[^>]*?>.*?</script>'si", // Strip out javascript<?
            "'<img.*?/>'si", // Strip out img tags
            "'<[\/\!]*?[^<>]*?>'si", // Strip out HTML tags<?
            "'([\r\n])[\s]+'", // Strip out white space
            "'&(quot|#34);'i", // Replace HTML entities
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
            //"'&#(\d+);'e"
        );
        // evaluate as php
        $replace = array(
            "",
            "",
            "",
            "\\1",
            "\"",
            "&",
            "<",
            ">",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            //"chr(\\1)"
        );
        $text = preg_replace($search, $replace, $document);
        return $text;
    }
}
