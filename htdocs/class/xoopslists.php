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
 * XOOPS listing utilities
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     class
 * @since       2.0.0
 * @version     $Id$
 */

defined('XOOPS_INITIALIZED') or die('Restricted access');

/**
 * XoopsLists
 *
 * @author     John Neill <catzwolf@xoops.org>
 * @copyright  copyright (c) XOOPS.org
 * @package    Xoops\Core
 * @subpackage Lists
 * @access public
 */
class xoopslists
{
    /**
     * @static
     * @return array
     */
    public static function getTimeZoneList()
    {
        $time_zone_list = array(
            '-12'  => XoopsLocale::L_TZ_GMTM12,
            '-11'  => XoopsLocale::L_TZ_GMTM11,
            '-10'  => XoopsLocale::L_TZ_GMTM10,
            '-9'   => XoopsLocale::L_TZ_GMTM9,
            '-8'   => XoopsLocale::L_TZ_GMTM8,
            '-7'   => XoopsLocale::L_TZ_GMTM7,
            '-6'   => XoopsLocale::L_TZ_GMTM6,
            '-5'   => XoopsLocale::L_TZ_GMTM5,
            '-4'   => XoopsLocale::L_TZ_GMTM4,
            '-3.5' => XoopsLocale::L_TZ_GMTM35,
            '-3'   => XoopsLocale::L_TZ_GMTM3,
            '-2'   => XoopsLocale::L_TZ_GMTM2,
            '-1'   => XoopsLocale::L_TZ_GMTM1,
            '0'    => XoopsLocale::L_TZ_GMT0,
            '1'    => XoopsLocale::L_TZ_GMTP1,
            '2'    => XoopsLocale::L_TZ_GMTP2,
            '3'    => XoopsLocale::L_TZ_GMTP3,
            '3.5'  => XoopsLocale::L_TZ_GMTP35,
            '4'    => XoopsLocale::L_TZ_GMTP4,
            '4.5'  => XoopsLocale::L_TZ_GMTP45,
            '5'    => XoopsLocale::L_TZ_GMTP5,
            '5.5'  => XoopsLocale::L_TZ_GMTP55,
            '6'    => XoopsLocale::L_TZ_GMTP6,
            '7'    => XoopsLocale::L_TZ_GMTP7,
            '8'    => XoopsLocale::L_TZ_GMTP8,
            '9'    => XoopsLocale::L_TZ_GMTP9,
            '9.5'  => XoopsLocale::L_TZ_GMTP95,
            '10'   => XoopsLocale::L_TZ_GMTP10,
            '11'   => XoopsLocale::L_TZ_GMTP11,
            '12'   => XoopsLocale::L_TZ_GMTP12
        );

        return $time_zone_list;
    }

    /**
     * gets list of themes folder from themes directory
     *
     * @static
     * @return array
     */
    public static function getThemesList()
    {
        return XoopsLists::getDirListAsArray(XOOPS_THEME_PATH . '/');
    }

    /**
     * gets a list of module folders from the modules directory
     *
     * @static
     * @return array
     */
    public static function getModulesList()
    {
        return XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/modules/');
    }

    /**
     * gets list of editors folder from xoopseditor directory
     *
     * @static
     * @return array
     */
    public static function getEditorList()
    {
        return XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor/');
    }

    /**
     * gets list of name of directories inside a directory
     *
     * @static
     *
     * @param string $dirname
     *
     * @return array
     */
    public static function getDirListAsArray($dirname)
    {
        $ignored = array(
            'cvs',
            '_darcs'
        );
        $list = array();
        if (substr($dirname, -1) != '/') {
            $dirname .= '/';
        }
        if (is_dir($dirname) AND $handle = opendir($dirname)) {
            while ($file = readdir($handle)) {
                if (substr($file, 0, 1) == '.' || in_array(strtolower($file), $ignored)) {
                    continue;
                }
                if (is_dir($dirname . $file)) {
                    $list[$file] = $file;
                }
            }
            closedir($handle);
            asort($list);
            reset($list);
        }

        return $list;
    }

    /**
     * gets list of all files in a directory
     *
     * @static
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    public static function getFileListAsArray($dirname, $prefix = '')
    {
        $filelist = array();
        if (substr($dirname, -1) == '/') {
            $dirname = substr($dirname, 0, -1);
        }
        if (is_dir($dirname) && $handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if (!preg_match('/^[\.]{1,2}$/', $file) && is_file($dirname . '/' . $file)) {
                    $file = $prefix . $file;
                    $filelist[$file] = $file;
                }
            }
            closedir($handle);
            asort($filelist);
            reset($filelist);
        }

        return $filelist;
    }

    /**
     * gets list of image file names in a directory
     *
     * @static
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    public static function getImgListAsArray($dirname, $prefix = '')
    {
        $filelist = array();
        if (is_dir($dirname) AND $handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if (preg_match('/\.(gif|jpg|jpeg|png|swf)$/i', $file)) {
                    $file = $prefix . $file;
                    $filelist[$file] = $file;
                }
            }
            closedir($handle);
            asort($filelist);
            reset($filelist);
        }

        return $filelist;
    }

    /**
     * gets list of html file names in a certain directory
     *
     * @static
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    public static function getHtmlListAsArray($dirname, $prefix = '')
    {
        $filelist = array();
        if (is_dir($dirname) AND $handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if ((preg_match('/\.(htm|html|xhtml)$/i', $file) && !is_dir($file))) {
                    $file = $prefix . $file;
                    $filelist[$file] = $prefix . $file;
                }
            }
            closedir($handle);
            asort($filelist);
            reset($filelist);
        }

        return $filelist;
    }

    /**
     * gets list of avatar file names in a certain directory
     *                             if directory is not specified, default directory will be searched
     *
     * @static
     *
     * @param string $avatar_dir
     *
     * @return array
     */
    public static function getAvatarsList($avatar_dir = '')
    {
        if ($avatar_dir != '') {
            $avatars = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/images/avatar/' . $avatar_dir . '/', $avatar_dir . '/');
        } else {
            $avatars = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/images/avatar/');
        }

        return $avatars;
    }

    /**
     * gets list of all avatar image files inside default avatars directory
     *
     * @static
     * @return array|bool
     */
    public static function getAllAvatarsList()
    {
        $avatars = array();
        $dirlist = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/images/avatar/');
        if (count($dirlist) > 0) {
            foreach ($dirlist as $dir) {
                $avatars[$dir] = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/images/avatar/' . $dir . '/', $dir . '/');
            }
        } else {
            return false;
        }

        return $avatars;
    }

    /**
     * gets list of subject icon image file names in a certain directory
     *                             if directory is not specified, default directory will be searched
     *
     * @static
     *
     * @param string $sub_dir
     *
     * @return array
     */
    public static function getSubjectsList($sub_dir = '')
    {
        if ($sub_dir != '') {
            $subjects = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/images/subject/' . $sub_dir, $sub_dir . '/');
        } else {
            $subjects = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/images/subject/');
        }

        return $subjects;
    }

    /**
     * gets list of language folders inside default language directory
     *
     * @static
     * @return array
     */
    public static function getLangList()
    {
        $lang_list = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/language/');

        return $lang_list;
    }

    /**
     * gets list of locale folders inside default language directory
     *
     * @static
     * @return array
     */
    public static function getLocaleList()
    {
        $lang_list = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/locale/');

        return $lang_list;
    }

    /**
     * XoopsLists::getCountryList()
     *
     * @static
     * @return array
     */
    public static function getCountryList()
    {
        $country_list = array(
            ""   => "-",
            "AD" => XoopsLocale::L_COUNTRY_AD,
            "AE" => XoopsLocale::L_COUNTRY_AE,
            "AF" => XoopsLocale::L_COUNTRY_AF,
            "AG" => XoopsLocale::L_COUNTRY_AG,
            "AI" => XoopsLocale::L_COUNTRY_AI,
            "AL" => XoopsLocale::L_COUNTRY_AL,
            "AM" => XoopsLocale::L_COUNTRY_AM,
            "AN" => XoopsLocale::L_COUNTRY_AN,
            "AO" => XoopsLocale::L_COUNTRY_AO,
            "AQ" => XoopsLocale::L_COUNTRY_AQ,
            "AR" => XoopsLocale::L_COUNTRY_AR,
            "AS" => XoopsLocale::L_COUNTRY_AS,
            "AT" => XoopsLocale::L_COUNTRY_AT,
            "AU" => XoopsLocale::L_COUNTRY_AU,
            "AW" => XoopsLocale::L_COUNTRY_AW,
            "AX" => XoopsLocale::L_COUNTRY_AX,
            "AZ" => XoopsLocale::L_COUNTRY_AZ,
            "BA" => XoopsLocale::L_COUNTRY_BA,
            "BB" => XoopsLocale::L_COUNTRY_BB,
            "BD" => XoopsLocale::L_COUNTRY_BD,
            "BE" => XoopsLocale::L_COUNTRY_BE,
            "BF" => XoopsLocale::L_COUNTRY_BF,
            "BG" => XoopsLocale::L_COUNTRY_BG,
            "BH" => XoopsLocale::L_COUNTRY_BH,
            "BI" => XoopsLocale::L_COUNTRY_BI,
            "BJ" => XoopsLocale::L_COUNTRY_BJ,
            "BL" => XoopsLocale::L_COUNTRY_BL,
            "BM" => XoopsLocale::L_COUNTRY_BM,
            "BN" => XoopsLocale::L_COUNTRY_BN,
            "BO" => XoopsLocale::L_COUNTRY_BO,
            "BR" => XoopsLocale::L_COUNTRY_BR,
            "BS" => XoopsLocale::L_COUNTRY_BS,
            "BT" => XoopsLocale::L_COUNTRY_BT,
            "BV" => XoopsLocale::L_COUNTRY_BV,
            "BW" => XoopsLocale::L_COUNTRY_BW,
            "BY" => XoopsLocale::L_COUNTRY_BY,
            "BZ" => XoopsLocale::L_COUNTRY_BZ,
            "CA" => XoopsLocale::L_COUNTRY_CA,
            "CC" => XoopsLocale::L_COUNTRY_CC,
            "CD" => XoopsLocale::L_COUNTRY_CD,
            "CF" => XoopsLocale::L_COUNTRY_CF,
            "CG" => XoopsLocale::L_COUNTRY_CG,
            "CH" => XoopsLocale::L_COUNTRY_CH,
            "CI" => XoopsLocale::L_COUNTRY_CI,
            "CK" => XoopsLocale::L_COUNTRY_CK,
            "CL" => XoopsLocale::L_COUNTRY_CL,
            "CM" => XoopsLocale::L_COUNTRY_CM,
            "CN" => XoopsLocale::L_COUNTRY_CN,
            "CO" => XoopsLocale::L_COUNTRY_CO,
            "CR" => XoopsLocale::L_COUNTRY_CR,
            "CS" => XoopsLocale::L_COUNTRY_CS, //  Not listed in ISO 3166
            "CU" => XoopsLocale::L_COUNTRY_CU,
            "CV" => XoopsLocale::L_COUNTRY_CV,
            "CX" => XoopsLocale::L_COUNTRY_CX,
            "CY" => XoopsLocale::L_COUNTRY_CY,
            "CZ" => XoopsLocale::L_COUNTRY_CZ,
            "DE" => XoopsLocale::L_COUNTRY_DE,
            "DJ" => XoopsLocale::L_COUNTRY_DJ,
            "DK" => XoopsLocale::L_COUNTRY_DK,
            "DM" => XoopsLocale::L_COUNTRY_DM,
            "DO" => XoopsLocale::L_COUNTRY_DO,
            "DZ" => XoopsLocale::L_COUNTRY_DZ,
            "EC" => XoopsLocale::L_COUNTRY_EC,
            "EE" => XoopsLocale::L_COUNTRY_EE,
            "EG" => XoopsLocale::L_COUNTRY_EG,
            "EH" => XoopsLocale::L_COUNTRY_EH,
            "ER" => XoopsLocale::L_COUNTRY_ER,
            "ES" => XoopsLocale::L_COUNTRY_ES,
            "ET" => XoopsLocale::L_COUNTRY_ET,
            "FI" => XoopsLocale::L_COUNTRY_FI,
            "FJ" => XoopsLocale::L_COUNTRY_FJ,
            "FK" => XoopsLocale::L_COUNTRY_FK,
            "FM" => XoopsLocale::L_COUNTRY_FM,
            "FO" => XoopsLocale::L_COUNTRY_FO,
            "FR" => XoopsLocale::L_COUNTRY_FR,
            "FX" => XoopsLocale::L_COUNTRY_FX, //  Not listed in ISO 3166
            "GA" => XoopsLocale::L_COUNTRY_GA,
            "GB" => XoopsLocale::L_COUNTRY_GB,
            "GD" => XoopsLocale::L_COUNTRY_GD,
            "GE" => XoopsLocale::L_COUNTRY_GE,
            "GF" => XoopsLocale::L_COUNTRY_GF,
            "GG" => XoopsLocale::L_COUNTRY_GG,
            "GH" => XoopsLocale::L_COUNTRY_GH,
            "GI" => XoopsLocale::L_COUNTRY_GI,
            "GL" => XoopsLocale::L_COUNTRY_GL,
            "GM" => XoopsLocale::L_COUNTRY_GM,
            "GN" => XoopsLocale::L_COUNTRY_GN,
            "GP" => XoopsLocale::L_COUNTRY_GP,
            "GQ" => XoopsLocale::L_COUNTRY_GQ,
            "GR" => XoopsLocale::L_COUNTRY_GR,
            "GS" => XoopsLocale::L_COUNTRY_GS,
            "GT" => XoopsLocale::L_COUNTRY_GT,
            "GU" => XoopsLocale::L_COUNTRY_GU,
            "GW" => XoopsLocale::L_COUNTRY_GW,
            "GY" => XoopsLocale::L_COUNTRY_GY,
            "HK" => XoopsLocale::L_COUNTRY_HK,
            "HM" => XoopsLocale::L_COUNTRY_HM,
            "HN" => XoopsLocale::L_COUNTRY_HN,
            "HR" => XoopsLocale::L_COUNTRY_HR,
            "HT" => XoopsLocale::L_COUNTRY_HT,
            "HU" => XoopsLocale::L_COUNTRY_HU,
            "ID" => XoopsLocale::L_COUNTRY_ID,
            "IE" => XoopsLocale::L_COUNTRY_IE,
            "IL" => XoopsLocale::L_COUNTRY_IL,
            "IM" => XoopsLocale::L_COUNTRY_IM,
            "IN" => XoopsLocale::L_COUNTRY_IN,
            "IO" => XoopsLocale::L_COUNTRY_IO,
            "IQ" => XoopsLocale::L_COUNTRY_IQ,
            "IR" => XoopsLocale::L_COUNTRY_IR,
            "IS" => XoopsLocale::L_COUNTRY_IS,
            "IT" => XoopsLocale::L_COUNTRY_IT,
            "JM" => XoopsLocale::L_COUNTRY_JM,
            "JO" => XoopsLocale::L_COUNTRY_JO,
            "JP" => XoopsLocale::L_COUNTRY_JP,
            "KE" => XoopsLocale::L_COUNTRY_KE,
            "KG" => XoopsLocale::L_COUNTRY_KG,
            "KH" => XoopsLocale::L_COUNTRY_KH,
            "KI" => XoopsLocale::L_COUNTRY_KI,
            "KM" => XoopsLocale::L_COUNTRY_KM,
            "KN" => XoopsLocale::L_COUNTRY_KN,
            "KP" => XoopsLocale::L_COUNTRY_KP,
            "KR" => XoopsLocale::L_COUNTRY_KR,
            "KW" => XoopsLocale::L_COUNTRY_KW,
            "KY" => XoopsLocale::L_COUNTRY_KY,
            "KZ" => XoopsLocale::L_COUNTRY_KZ,
            "LA" => XoopsLocale::L_COUNTRY_LA,
            "LB" => XoopsLocale::L_COUNTRY_LB,
            "LC" => XoopsLocale::L_COUNTRY_LC,
            "LI" => XoopsLocale::L_COUNTRY_LI,
            "LK" => XoopsLocale::L_COUNTRY_LK,
            "LR" => XoopsLocale::L_COUNTRY_LR,
            "LS" => XoopsLocale::L_COUNTRY_LS,
            "LT" => XoopsLocale::L_COUNTRY_LT,
            "LU" => XoopsLocale::L_COUNTRY_LU,
            "LV" => XoopsLocale::L_COUNTRY_LV,
            "LY" => XoopsLocale::L_COUNTRY_LY,
            "MA" => XoopsLocale::L_COUNTRY_MA,
            "MC" => XoopsLocale::L_COUNTRY_MC,
            "MD" => XoopsLocale::L_COUNTRY_MD,
            "ME" => XoopsLocale::L_COUNTRY_ME,
            "MF" => XoopsLocale::L_COUNTRY_MF,
            "MG" => XoopsLocale::L_COUNTRY_MG,
            "MH" => XoopsLocale::L_COUNTRY_MH,
            "MK" => XoopsLocale::L_COUNTRY_MK,
            "ML" => XoopsLocale::L_COUNTRY_ML,
            "MM" => XoopsLocale::L_COUNTRY_MM,
            "MN" => XoopsLocale::L_COUNTRY_MN,
            "MO" => XoopsLocale::L_COUNTRY_MO,
            "MP" => XoopsLocale::L_COUNTRY_MP,
            "MQ" => XoopsLocale::L_COUNTRY_MQ,
            "MR" => XoopsLocale::L_COUNTRY_MR,
            "MS" => XoopsLocale::L_COUNTRY_MS,
            "MT" => XoopsLocale::L_COUNTRY_MT,
            "MU" => XoopsLocale::L_COUNTRY_MU,
            "MV" => XoopsLocale::L_COUNTRY_MV,
            "MW" => XoopsLocale::L_COUNTRY_MW,
            "MX" => XoopsLocale::L_COUNTRY_MX,
            "MY" => XoopsLocale::L_COUNTRY_MY,
            "MZ" => XoopsLocale::L_COUNTRY_MZ,
            "NA" => XoopsLocale::L_COUNTRY_NA,
            "NC" => XoopsLocale::L_COUNTRY_NC,
            "NE" => XoopsLocale::L_COUNTRY_NE,
            "NF" => XoopsLocale::L_COUNTRY_NF,
            "NG" => XoopsLocale::L_COUNTRY_NG,
            "NI" => XoopsLocale::L_COUNTRY_NI,
            "NL" => XoopsLocale::L_COUNTRY_NL,
            "NO" => XoopsLocale::L_COUNTRY_NO,
            "NP" => XoopsLocale::L_COUNTRY_NP,
            "NR" => XoopsLocale::L_COUNTRY_NR,
            "NT" => XoopsLocale::L_COUNTRY_NT, //  Not listed in ISO 3166
            "NU" => XoopsLocale::L_COUNTRY_NU,
            "NZ" => XoopsLocale::L_COUNTRY_NZ,
            "OM" => XoopsLocale::L_COUNTRY_OM,
            "PA" => XoopsLocale::L_COUNTRY_PA,
            "PE" => XoopsLocale::L_COUNTRY_PE,
            "PF" => XoopsLocale::L_COUNTRY_PF,
            "PG" => XoopsLocale::L_COUNTRY_PG,
            "PH" => XoopsLocale::L_COUNTRY_PH,
            "PK" => XoopsLocale::L_COUNTRY_PK,
            "PL" => XoopsLocale::L_COUNTRY_PL,
            "PM" => XoopsLocale::L_COUNTRY_PM,
            "PN" => XoopsLocale::L_COUNTRY_PN,
            "PR" => XoopsLocale::L_COUNTRY_PR,
            "PS" => XoopsLocale::L_COUNTRY_PS,
            "PT" => XoopsLocale::L_COUNTRY_PT,
            "PW" => XoopsLocale::L_COUNTRY_PW,
            "PY" => XoopsLocale::L_COUNTRY_PY,
            "QA" => XoopsLocale::L_COUNTRY_QA,
            "RE" => XoopsLocale::L_COUNTRY_RE,
            "RO" => XoopsLocale::L_COUNTRY_RO,
            "RS" => XoopsLocale::L_COUNTRY_RS,
            "RU" => XoopsLocale::L_COUNTRY_RU,
            "RW" => XoopsLocale::L_COUNTRY_RW,
            "SA" => XoopsLocale::L_COUNTRY_SA,
            "SB" => XoopsLocale::L_COUNTRY_SB,
            "SC" => XoopsLocale::L_COUNTRY_SC,
            "SD" => XoopsLocale::L_COUNTRY_SD,
            "SE" => XoopsLocale::L_COUNTRY_SE,
            "SG" => XoopsLocale::L_COUNTRY_SG,
            "SH" => XoopsLocale::L_COUNTRY_SH,
            "SI" => XoopsLocale::L_COUNTRY_SI,
            "SJ" => XoopsLocale::L_COUNTRY_SJ,
            "SK" => XoopsLocale::L_COUNTRY_SK,
            "SL" => XoopsLocale::L_COUNTRY_SL,
            "SM" => XoopsLocale::L_COUNTRY_SM,
            "SN" => XoopsLocale::L_COUNTRY_SN,
            "SO" => XoopsLocale::L_COUNTRY_SO,
            "SR" => XoopsLocale::L_COUNTRY_SR,
            "ST" => XoopsLocale::L_COUNTRY_ST,
            "SU" => XoopsLocale::L_COUNTRY_SU, //  Not listed in ISO 3166
            "SV" => XoopsLocale::L_COUNTRY_SV,
            "SY" => XoopsLocale::L_COUNTRY_SY,
            "SZ" => XoopsLocale::L_COUNTRY_SZ,
            "TC" => XoopsLocale::L_COUNTRY_TC,
            "TD" => XoopsLocale::L_COUNTRY_TD,
            "TF" => XoopsLocale::L_COUNTRY_TF,
            "TG" => XoopsLocale::L_COUNTRY_TG,
            "TH" => XoopsLocale::L_COUNTRY_TH,
            "TJ" => XoopsLocale::L_COUNTRY_TJ,
            "TK" => XoopsLocale::L_COUNTRY_TK,
            "TL" => XoopsLocale::L_COUNTRY_TL,
            "TM" => XoopsLocale::L_COUNTRY_TM,
            "TN" => XoopsLocale::L_COUNTRY_TN,
            "TO" => XoopsLocale::L_COUNTRY_TO,
            "TP" => XoopsLocale::L_COUNTRY_TP, //  Not listed in ISO 3166
            "TR" => XoopsLocale::L_COUNTRY_TR,
            "TT" => XoopsLocale::L_COUNTRY_TT,
            "TV" => XoopsLocale::L_COUNTRY_TV,
            "TW" => XoopsLocale::L_COUNTRY_TW,
            "TZ" => XoopsLocale::L_COUNTRY_TZ,
            "UA" => XoopsLocale::L_COUNTRY_UA,
            "UG" => XoopsLocale::L_COUNTRY_UG,
            "UK" => XoopsLocale::L_COUNTRY_UK, //  Not listed in ISO 3166
            "UM" => XoopsLocale::L_COUNTRY_UM,
            "US" => XoopsLocale::L_COUNTRY_US,
            "UY" => XoopsLocale::L_COUNTRY_UY,
            "UZ" => XoopsLocale::L_COUNTRY_UZ,
            "VA" => XoopsLocale::L_COUNTRY_VA,
            "VC" => XoopsLocale::L_COUNTRY_VC,
            "VE" => XoopsLocale::L_COUNTRY_VE,
            "VG" => XoopsLocale::L_COUNTRY_VG,
            "VI" => XoopsLocale::L_COUNTRY_VI,
            "VN" => XoopsLocale::L_COUNTRY_VN,
            "VU" => XoopsLocale::L_COUNTRY_VU,
            "WF" => XoopsLocale::L_COUNTRY_WF,
            "WS" => XoopsLocale::L_COUNTRY_WS,
            "YE" => XoopsLocale::L_COUNTRY_YE,
            "YT" => XoopsLocale::L_COUNTRY_YT,
            "YU" => XoopsLocale::L_COUNTRY_YU, // Not listed in ISO 3166
            "ZA" => XoopsLocale::L_COUNTRY_ZA,
            "ZM" => XoopsLocale::L_COUNTRY_ZM,
            "ZR" => XoopsLocale::L_COUNTRY_ZR, // Not listed in ISO 3166
            "ZW" => XoopsLocale::L_COUNTRY_ZW
        );
        asort($country_list);
        reset($country_list);

        return $country_list;
    }

    /**
     * XoopsLists::getHtmlList()
     * This Function is no longer being used by the core
     *
     * @static
     * @return array
     */
    public static function getHtmlList()
    {
        $html_list = array(
            'a'          => '&lt;a&gt;',
            'abbr'       => '&lt;abbr&gt;',
            'acronym'    => '&lt;acronym&gt;',
            'address'    => '&lt;address&gt;',
            'b'          => '&lt;b&gt;',
            'bdo'        => '&lt;bdo&gt;',
            'big'        => '&lt;big&gt;',
            'blockquote' => '&lt;blockquote&gt;',
            'br'         => '&lt;br&gt;',
            'caption'    => '&lt;caption&gt;',
            'cite'       => '&lt;cite&gt;',
            'code'       => '&lt;code&gt;',
            'col'        => '&lt;col&gt;',
            'colgroup'   => '&lt;colgroup&gt;',
            'dd'         => '&lt;dd&gt;',
            'del'        => '&lt;del&gt;',
            'dfn'        => '&lt;dfn&gt;',
            'div'        => '&lt;div&gt;',
            'dl'         => '&lt;dl&gt;',
            'dt'         => '&lt;dt&gt;',
            'em'         => '&lt;em&gt;',
            'font'       => '&lt;font&gt;',
            'h1'         => '&lt;h1&gt;',
            'h2'         => '&lt;h2&gt;',
            'h3'         => '&lt;h3&gt;',
            'h4'         => '&lt;h4&gt;',
            'h5'         => '&lt;h5&gt;',
            'h6'         => '&lt;h6&gt;',
            'hr'         => '&lt;hr&gt;',
            'i'          => '&lt;i&gt;',
            'img'        => '&lt;img&gt;',
            'ins'        => '&lt;ins&gt;',
            'kbd'        => '&lt;kbd&gt;',
            'li'         => '&lt;li&gt;',
            'map'        => '&lt;map&gt;',
            'object'     => '&lt;object&gt;',
            'ol'         => '&lt;ol&gt;',
            'p'          => '&lt;p&gt;',
            'pre'        => '&lt;pre&gt;',
            's'          => '&lt;s&gt;',
            'samp'       => '&lt;samp&gt;',
            'small'      => '&lt;small&gt;',
            'span'       => '&lt;span&gt;',
            'strike'     => '&lt;strike&gt;',
            'strong'     => '&lt;strong&gt;',
            'sub'        => '&lt;sub&gt;',
            'sup'        => '&lt;sup&gt;',
            'table'      => '&lt;table&gt;',
            'tbody'      => '&lt;tbody&gt;',
            'td'         => '&lt;td&gt;',
            'tfoot'      => '&lt;tfoot&gt;',
            'th'         => '&lt;th&gt;',
            'thead'      => '&lt;thead&gt;',
            'tr'         => '&lt;tr&gt;',
            'tt'         => '&lt;tt&gt;',
            'u'          => '&lt;u&gt;',
            'ul'         => '&lt;ul&gt;',
            'var'        => '&lt;var&gt;'
        );
        asort($html_list);
        reset($html_list);

        return $html_list;
    }

    /**
     * XoopsLists::getUserRankList()
     *
     * @todo create handler for ranks
     * @static
     * @return array
     */
    public static function getUserRankList()
    {
        $db = Xoops::getInstance()->db();
        $myts = MyTextSanitizer::getInstance();

        $ret = array();

        $sql = $db->createXoopsQueryBuilder();
        $eb = $sql->expr();
        $sql->select('rank_id')
            ->addSelect('rank_title')
            ->fromPrefix('ranks', 'r')
            ->where($eb->eq('rank_special', ':rankspecial'))
            ->orderBy('rank_title')
            ->setParameter(':rankspecial', 1);

        $result = $sql->execute();
        while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
            $ret[$myrow['rank_id']] = $myts->htmlspecialchars($myrow['rank_title']);
        }

        return $ret;
    }
}
