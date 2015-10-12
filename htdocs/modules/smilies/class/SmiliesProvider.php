<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Service\AbstractContract;
use Xoops\Core\Service\Contract\EmojiInterface;
use Xoops\Core\Service\Response;

/**
 * Smilies provider for service manager
 *
 * @category  SmiliesProvider
 * @package   Smilies
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SmiliesProvider extends AbstractContract implements EmojiInterface
{
    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    public function getName()
    {
        return 'smilies';
    }

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Traditional XOOPS Smilies';
    }

    /**
     * renderEmoji - given a string of source text being built for display, perform any processing of Emoji
     * references required to display the intended imagery.
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $buffer   source text to be processed
     *
     * @return void - $response->value set to processed buffer
     */
    public function renderEmoji(Response $response, $buffer)
    {
        $emojiList = $this->getSmileyList();
        $emojiName = array_column($emojiList, 'name');
        $emojiRendered = array_column($emojiList, 'rendered');

        $processedBuffer = str_replace($emojiName, $emojiRendered, $buffer);

        $response->setValue($processedBuffer);
    }

    /**
     * getEmojiList - return a list of available emoji
     *
     * @param Response $response \Xoops\Core\Service\Response object
     *
     * @return void - $response->value set to array of emoji information
     *                    'name'        => (string) code that represents the emoji, i.e. ":wink:"
     *                    'description' => (string) description
     *                    'rendered'    => (string) valid HTML to display a rendering of the emoji
     */
    public function getEmojiList(Response $response)
    {
        $response->setValue($this->getSmileyList());
    }

    /**
     * renderEmojiSelector - provide emoji selector support for editing
     *
     * This should return an HTML string that, when displayed, will provide a link to an emoji selector.
     * Additionally, this should perform any additional tasks required to make the link function, such
     * as adding script or stylesheet assets to the active theme.
     *
     * @param Response $response   \Xoops\Core\Service\Response object
     * @param string   $identifier element identifier to receive emoji from selector
     *
     * @return void - $response->value (string) HTML code to launch the emoji selector, i.e. button
     */
    public function renderEmojiSelector(Response $response, $identifier)
    {
        $selector =  '<img src="' . \XoopsBaseConfig::get('url') . '/images/smiley.gif" alt="'
            . \XoopsLocale::SMILIES . '" title="' . \XoopsLocale::SMILIES . '" onclick=\'openWithSelfMain("'
            . \XoopsBaseConfig::get('url') . '/modules/smilies/popup.php?target=' . $identifier
            . '","smilies",300,650);\' onmouseover=\'style.cursor="hand"\'/>&nbsp;';

        $response->setValue($selector);
    }

    /**
     * get list of smilies in emoji format
     *
     * @return array emoji list
     */
    private function getSmileyList()
    {
        static $emojiList = null;

        if ($emojiList === null) {
            $smiliesArray = \Xoops::getInstance()->getModuleHandler('smiley', 'smilies')->getActiveSmilies(false);
            $emojiList = [];
            foreach ($smiliesArray as $smile) {
                $emoji['name'] = $smile['smiley_code'];
                $emoji['description'] = $smile['smiley_emotion'];
                $emoji['rendered'] =
                    '<img src="' . $smile['smiley_url'] . '" alt="' . $smile['smiley_emotion'] . '" />';
                $emojiList[] = $emoji;
            }
        }
        return $emojiList;
    }
}
