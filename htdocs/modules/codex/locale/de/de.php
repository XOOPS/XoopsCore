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
class CodexLocaleDe
{
    // Module
    const MODULE_NAME = 'Codex';
    const MODULE_DESC = 'Code Beispiele für Entwickler';

    // Configs
    const UCONF_ITEM1 = 'Element 1';
    const UCONF_ITEM1_DESC = 'Element 1 desc';
    const UCONF_ITEM2 = 'Element 2';
    const UCONF_ITEM2_DESC = 'Element 2 desc';
    const UCONF_CAT1 = 'Kat 1';
    const UCONF_CAT1_DESC = 'Kat 1 desc';
    const UCONF_CAT2 = 'Kat 2';
    const UCONF_CAT2_DESC = 'Kat 2 desc';

    const MY_DOG_NAME_AND_AGE = 'Mein Hund heißt {name}. Er ist {years,plural,=0{noch nicht geboren} =1{nur ein Jahr alt} other{# Jahre alt}}';
    const YOU_LIKED_THIS = '
        Es hat Ihnen {likeCount,plural, offset: 1 =0{nicht gefallen} =1{gefallen}
        0{und keiner anderen Person gefallen}
        one{und einer anderen Person gefallen}
        other{und # anderen Personen gefallen}
    }';

    const GENDER = '{name} ist ein{gender,select,woman{e Frau} man{ Mann} dog{ Hund} picture{ Bild} other{ Ding}} und {gender,select,woman{sie} man{er} dog{er} picture{es} other{es}} liebt XOOPS!';
}
