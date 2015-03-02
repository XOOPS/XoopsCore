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
 * Center Form Class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Protector
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class ProtectorCenterForm extends Xoops\Form\ThemeForm
{
    /**
     * @param null $obj
     */
    public function __construct($obj = null)
    {
    }

    /**
     * Maintenance Form
     * @return void
     */
    public function getPrefIp($bad_ips4disp, $group1_ips4disp)
    {
        global $xoopsDB;
        $db = $xoopsDB;
        $protector = Protector::getInstance($db->conn);
        require_once dirname(__DIR__) . '/gtickets.php';

        parent::__construct('', "form_prefip", "center.php", 'post', true);

        $bad_ips = new Xoops\Form\TextArea(_AM_TH_BADIPS, 'bad_ips', $bad_ips4disp, 3, 90);
        $bad_ips->setDescription('<br />' . htmlspecialchars($protector->get_filepath4badips()));
        $bad_ips->setClass('span3');
        $this->addElement($bad_ips);

        $group1_ips = new Xoops\Form\TextArea(_AM_TH_GROUP1IPS, 'group1_ips', $group1_ips4disp, 3, 90);
        $group1_ips->setDescription('<br />' . htmlspecialchars($protector->get_filepath4group1ips()));
        $group1_ips->setClass('span3');
        $this->addElement($group1_ips);
        $formTicket = new xoopsGTicket;
        $this->addElement(new Xoops\Form\Hidden("action", "update_ips"));
        $ticket = $formTicket->getTicketXoopsForm(__LINE__, 1800, 'protector_admin');
        $this->addElement($ticket);
        $this->addElement(new Xoops\Form\Button('', "submit_prefip", XoopsLocale::A_SUBMIT, "submit"));
    }
}
