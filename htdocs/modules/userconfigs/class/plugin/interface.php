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
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

interface UserconfigsPluginInterface
{
    /**
     * Expects an array of arrays containing:
     *
     * name,        Name of the config
     * title,       Display name for the config, use constant
     * description, Description for the config, use constant
     * formtype,    Form to use for the config
     * default,     Default value for the config
     * options,     Options available for the config
     * category,    Category for this config, use the unique identifier set on categories()
     */
    public function configs();

    /**
     * Expects an array of arrays containing:
     *
     * name,        Name of the category
     * description, Description for the category, use constant
     *
     * The keys must be unique identifiers
     */
    public function categories();
}

