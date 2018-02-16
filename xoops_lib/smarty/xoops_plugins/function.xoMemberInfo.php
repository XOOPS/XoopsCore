<?php
// $Id: function.xoMemberInfo.php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     xoMemberInfo
 * Version:  1.0
 * Author:   DuGris
 * Purpose:  Get member informations
 * Input:    infos  =   informations to be recovered in the profile of the member
 *                      if empty uname,name,email,user_avatar,url,user_icq,user_aim,user_yim,user_msnm,user_from,
 *                      user_occ, user_intrest, bio, user_sig will be recovered
 *
 *           assign =   variable to be initialized for the templates
 *
 *          I.e: Get all informations
 *              {xoMemberInfo assign=member_info}
 *
 *          I.e: Get uname, avatar and email
 *              {xoMemberInfo assign=member_info infos="uname|email|avatar"}
 * -------------------------------------------------------------
 */

function smarty_function_xoMemberInfo($params, &$smarty)
{
    $xoops = Xoops::getInstance();

    $time = time();
    $member_info = $_SESSION['xoops_member_info'];
    if (!$xoops->isUser()) {
        $member_info['uname'] = $xoops->getConfig('anonymous');
    } else {
        if (@empty($params['infos'])) {
            $params['infos'] = 'uname|name|email|user_avatar|url|user_icq|user_aim|user_yim|user_msnm|posts|user_from|user_occ|user_intrest|bio|user_sig';
        }
        $infos = explode("|", $params['infos']);

        if (!is_array($member_info)) {
            $member_info = array();
        }
        foreach ($infos as $info) {
            if (!array_key_exists($info, $member_info) && @$_SESSION['xoops_member_info'][$info . '_expire'] < $time) {
                $member_info[$info] = $xoops->user->getVar($info, 'E');
                $_SESSION['xoops_member_info'][$info] = $member_info[$info];
                $_SESSION['xoops_member_info'][$info . '_expire'] = $time + 60;
            }
        }
    }
    if (!@empty($params['assign'])) {
        $smarty->assign($params['assign'], $member_info);
    }
}
