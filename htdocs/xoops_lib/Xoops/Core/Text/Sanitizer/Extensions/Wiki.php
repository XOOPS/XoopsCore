<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Text\Sanitizer\Extensions;

use Xoops\Core\Text\Sanitizer;
use Xoops\Core\Text\Sanitizer\ExtensionAbstract;

/**
 * TextSanitizer extension
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Wiki extends ExtensionAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => false,
        'link' => 'modules/mediawiki/?title=%s', // The link to wiki module
        'template' => '<a href="%1$s" rel="external">%2$s</a>',
    ];

    /**
     * Provide button and javascript code used by the DhtmlTextArea
     *
     * @param string $textAreaId dom element id
     *
     * @return string[] editor button as HTML, supporting javascript
     */
    public function getDhtmlEditorSupport($textAreaId)
    {
        $buttonCode = $this->getEditorButtonHtml(
            $textAreaId,
            'wiki.gif',
            \XoopsLocale::WIKI,
            'xoopsCodeWiki',
            \XoopsLocale::WIKI_WORD_TO_LINK
        );

        $javascript = <<<EOH
            function xoopsCodeWiki(id, enterWikiPhrase){
                if (enterWikiPhrase == null) {
                    enterWikiPhrase = "Enter the word to be linked to Wiki:";
                }
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                }else {
                    var text = prompt(enterWikiPhrase, "");
                }
                var domobj = xoopsGetElementById(id);
                if ( text != null && text != "" ) {
                    var result = "[[" + text + "]]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
EOH;
        return [$buttonCode, $javascript];
    }

    /**
     * Register extension with the supplied sanitizer instance
     *
     * @return void
     */
    public function registerExtensionProcessing()
    {
        $this->ts->addPatternCallback(
            "/\[\[([^\]]*)\]\]/sU",
            [$this, 'decode']
        );
    }

    /**
     * build link to wiki page
     *
     * @param array $match from preg_match of wiki resource
     *
     * @return string
     */
    public function decode($match)
    {
        $wikiWord = $match[1];
        if (empty($wikiWord) || empty($this->config['link'])) {
            return $wikiWord;
        }
        $url = \Xoops::getInstance()->url(sprintf($this->config['link'], urlencode($wikiWord)));

        $newContent = sprintf($this->config['template'], $url, $wikiWord);
        return $newContent;
    }
}
