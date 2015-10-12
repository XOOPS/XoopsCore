<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service\Contract;

use Xoops\Core\Service\Response;
use Xoops\Core\Service\Manager;

/**
 * Emoji service interface
 *
 * @category  Xoops\Core\Service\Contract
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 */
interface EmojiInterface
{
    const MODE = Manager::MODE_EXCLUSIVE;

    /**
     * renderEmoji - given a string of source text being built for display, perform any processing of Emoji
     * references required to display the intended imagery.
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $buffer   source text to be processed
     *
     * @return void - $response->value set to processed buffer
     */
    public function renderEmoji(Response $response, $buffer);

    /**
     * getEmojiList - return a list of available emoji
     *
     * @param Response  $response \Xoops\Core\Service\Response object
     *
     * @return void - $response->value set to array of emoji information
     *                    'name'        => (string) code that represents the emoji, i.e. ":wink:"
     *                    'description' => (string) description
     *                    'rendered'    => (string) valid HTML to display a rendering of the emoji
     */
    public function getEmojiList(Response $response);

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
    public function renderEmojiSelector(Response $response, $identifier);

}
