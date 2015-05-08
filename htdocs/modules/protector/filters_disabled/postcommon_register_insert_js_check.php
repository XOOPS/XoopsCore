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
 * Protector
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class protector_postcommon_register_insert_js_check extends ProtectorFilterAbstract
{
    function execute()
    {
        ob_start(array($this, 'ob_filter'));

        if (!empty($_POST)) {
            if (!$this->checkValidate()) {
                die(_MD_PROTECTOR_TURNJAVASCRIPTON);
            }
        }

        return true;
    }

    // insert javascript into the registering form
    function ob_filter($s)
    {
        $antispam_htmls = $this->getHtml4Assign();

        return preg_replace('/<form[^>]*action=["\'](|#|register.php)["\'][^>]+>/i', '$0' . "\n" . $antispam_htmls['html_in_form'] . "\n" . $antispam_htmls['js_global'], $s, 1);
    }

    // import from D3forumAntispamDefault.clas.php

    /**
     * @param integer $time
     */
    function getMd5($time = null)
    {
        if (empty($time)) {
            $time = time();
        }
        return md5(gmdate('YmdH', $time) . XOOPS_DB_PREFIX . XOOPS_DB_NAME);
    }

    function getHtml4Assign()
    {
        $as_md5 = $this->getMd5();
        $as_md5array = preg_split('//', $as_md5, -1, PREG_SPLIT_NO_EMPTY);
        $as_md5shuffle = array();
        foreach ($as_md5array as $key => $val) {
            $as_md5shuffle[] = array(
                'key' => $key,
                'val' => $val
            );
        }
        shuffle($as_md5shuffle);
        $js_in_validate_function = "antispam_md5s=new Array(32);\n";
        foreach ($as_md5shuffle as $item) {
            $key = $item['key'];
            $val = $item['val'];
            $js_in_validate_function .= "antispam_md5s[$key]='$val';\n";
        }
        $js_in_validate_function .= "
            antispam_md5 = '' ;
            for( i = 0 ; i < 32 ; i ++ ) {
                antispam_md5 += antispam_md5s[i] ;
            }
            xoopsGetElementById('antispam_md5').value = antispam_md5 ;
        ";

        return array(
            'html_in_form' => '<input type="hidden" name="antispam_md5" id="antispam_md5" value="" />',
            'js_global'    => '<script type="text/javascript"><!--//' . "\n" . $js_in_validate_function . "\n" . '//--></script><noscript><div class="errorMsg">' . _MD_PROTECTOR_TURNJAVASCRIPTON . '</div></noscript>',
        );
    }

    function checkValidate()
    {
        $user_md5 = trim(@$_POST['antispam_md5']);

        // 2-3 hour margin
        if ($user_md5 != $this->getMd5() && $user_md5 != $this->getMd5(time() - 3600) && $user_md5 != $this->getMd5(time() - 7200)) {
            $this->errors[] = _MD_PROTECTOR_TURNJAVASCRIPTON;
            return false;
        }
        return true;
    }
}
