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
 * codex module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         codex
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          mamba <mambax7@gmail.com>
 */
class CodexLocalePl
{
    // Module
    const MODULE_NAME = "Codex";
    const MODULE_DESC = "Przykłady kodu dla programistów";

    // Configs
    const UCONF_ITEM1 = "Pozycja 1";
    const UCONF_ITEM1_DESC = "Pozycja 1 desc";
    const UCONF_ITEM2 = "Pozycja 2";
    const UCONF_ITEM2_DESC = "Pozycja 2 desc";
    const UCONF_CAT1 = "Cat 1";
    const UCONF_CAT1_DESC = "Cat 1 desc";
    const UCONF_CAT2 = "Cat 2";
    const UCONF_CAT2_DESC = "Cat 2 desc";

    const MY_DOG_NAME_AND_AGE = "Mój pies nazywa się {name}. On {years,plural,=0{nie jest jeszcze urodzony} =1{ma tylko jeden rok} =2{ma # lata}  =3{ma # lata}  =4{ma # lata} other{ma # lat}}";
    const YOU_LIKED_THIS = "
        Ty {likeCount,plural,
        offset: 1
        =0{tego nie lubiłeś}
        =1{to lubiłeś}
        0{nikt inny tego nie lubił} 
        one{i jedna inna osoba to lubiliście}
        other{i # inne osoby polubiły to}
    }";
    const GENDER = "{name} jest {gender,select,woman{kobietą} man{mężczyzną} dog{ psem} picture{ obrazem} other{ kimś}} i {gender,select,woman{ona} man{on} other{on}} kocha XOOPS!";

}
