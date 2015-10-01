<?php

/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * provide some utility methods for databases
 *
 * PHP version 5.3
 *
 * @category  Xoops\Class\Database\SqlUtility
 * @package   SqlUtility
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 2.6.0
 * @link      http://xoops.org
 * @since     2.6.0
 */

class SqlUtility
{
    /**
     * Function from phpMyAdmin (http://phpwizard.net/projects/phpMyAdmin/)
     *
     * Removes comment and splits large sql files into individual queries
     *
     * Last revision: September 23, 2001 - gandon
     *
     * @param array  &$ret the splitted sql commands
     * @param string $sql  the sql commands
     *
     * @return bool always true
     * @access public
     */
    public static function splitMySqlFile(&$ret, $sql)
    {
        $sql = trim($sql);
        $sql_len = strlen($sql);
        $string_start = '';
        $in_string = false;

        for ($i = 0; $i < $sql_len; ++$i) {
            $char = $sql[$i];
            if ($in_string) {
                while (true) {
                    $i = strpos($sql, $string_start, $i);
                    if (!$i) {
                        $ret[] = $sql;
                        return true;
                    } else {
                        if ($string_start == '`' || $sql[$i - 1] != '\\') {
                            $string_start = '';
                            $in_string = false;
                            break;
                        } else {
                            $j = 2;
                            $escaped_backslash = false;
                            while ($i - $j > 0 && $sql[$i - $j] == '\\') {
                                $escaped_backslash = !$escaped_backslash;
                                ++$j;
                            }
                            if ($escaped_backslash) {
                                $string_start = '';
                                $in_string = false;
                                break;
                            } else {
                                ++$i;
                            }
                        }
                    }
                }
            } else {
                if ($char == ';') {
                    $ret[] = substr($sql, 0, $i);
                    $sql = ltrim(substr($sql, min($i + 1, $sql_len)));
                    $sql_len = strlen($sql);
                    if ($sql_len) {
                        $i = -1;
                    } else {
                        return true;
                    }
                } else {
                    if (($char == '"') || ($char == '\'') || ($char == '`')) {
                        $in_string = true;
                        $string_start = $char;
                    } else {
                        if ($char == '#' || ($char == ' ' && $i > 1 && $sql[$i - 2] . $sql[$i - 1] == '--')) {
                            $start_of_comment = (($sql[$i] == '#') ? $i : $i - 2);
                            $end_of_comment = (strpos(' ' . $sql, "\012", $i + 2)) ? strpos(' ' . $sql, "\012", $i + 2)
                                : strpos(' ' . $sql, "\015", $i + 2);
                            if (!$end_of_comment) {
                                return true;
                            } else {
                                $sql = substr($sql, 0, $start_of_comment) . ltrim(substr($sql, $end_of_comment));
                                $sql_len = strlen($sql);
                                $i--;
                            }
                        }
                    }
                }
            }
        }

        if (!empty($sql) && trim($sql) != '') {
            $ret[] = $sql;
        }
        return true;
    }

    /**
     * add a prefix.'_' to all tablenames in a query
     *
     * @param string $query  valid SQL query string
     * @param string $prefix prefix to add to all table names
     *
     * @return mixed FALSE on failure
     */
    public static function prefixQuery($query, $prefix)
    {
        $pattern = "/^(INSERT[\s]+INTO|CREATE[\s]+TABLE|ALTER[\s]+TABLE|UPDATE)(\s)+([`]?)([^`\s]+)\\3(\s)+/siU";
        $pattern2 = "/^(DROP TABLE)(\s)+([`]?)([^`\s]+)\\3(\s)?$/siU";
        if (preg_match($pattern, $query, $matches)
            || preg_match($pattern2, $query, $matches)
        ) {
            $replace = "\\1 " . $prefix . "_\\4\\5";
            $matches[0] = preg_replace($pattern, $replace, $query);
            return $matches;
        }
        return false;
    }
}
