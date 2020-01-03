<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\ParameterType;
use Xmf\Metagen;
use Xoops\Module\Plugin\PluginAbstract;

/**
 * page module
 *
 * @author          Mage Grégory (AKA Mage)
 * @copyright       2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @since           2.6.0
 */
class PageSearchPlugin extends PluginAbstract implements SearchPluginInterface
{
    /**
     * search - search
     *
     * @param string[] $queryArray search terms
     * @param string   $andor      and/or how to treat search terms
     * @param int  $limit      max number to return
     * @param int  $offset     offset of first row to return
     * @param int  $userid     a specific user id to limit the query
     *
     * @return array of result items
     *           'title' => the item title
     *           'content' => brief content or summary
     *           'link' => link to visit item
     *           'time' => time modified (unix timestamp)
     *           'uid' => author uid
     *           'image' => icon for search display
     */
    public function search($queryArray, $andor, $limit, $offset, $userid)
    {
        $andor = 'and' === mb_strtolower($andor) ? 'and' : 'or';

        $qb = \Xoops::getInstance()->db()->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb->select('DISTINCT *')
            ->fromPrefix('page_content')
            ->where($eb->neq('content_status', '0'))
            ->orderBy('content_create', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        if (is_array($queryArray) && !empty($queryArray)) {
            $queryParts = [];
            foreach ($queryArray as $i => $q) {
                $qterm = ':qterm' . $i;
                $qb->setParameter($qterm, '%' . $q . '%', ParameterType::STRING);
                $queryParts[] = $eb->orX(
                    $eb->like('content_title', $qterm),
                    $eb->like('content_text', $qterm),
                    $eb->like('content_shorttext', $qterm)
                );
            }
            if ('and' === $andor) {
                $qb->andWhere(call_user_func_array([$eb, 'andX'], $queryParts));
            } else {
                $qb->andWhere(call_user_func_array([$eb, 'orX'], $queryParts));
            }
        } else {
            $qb->setParameter(':uid', (int) $userid, ParameterType::INTEGER);
            $qb->andWhere($eb->eq('content_author', ':uid'));
        }

        $myts = \Xoops\Core\Text\Sanitizer::getInstance();
        $items = [];
        $result = $qb->execute();
        while ($myrow = $result->fetch(FetchMode::ASSOCIATIVE)) {
            $content = $myrow['content_shorttext'] . '<br /><br />' . $myrow['content_text'];
            $content = $myts->displayTarea($content);
            $items[] = [
                'title' => $myrow['content_title'],
                'content' => Metagen::getSearchSummary($content, $queryArray),
                'link' => 'viewpage.php?id=' . $myrow['content_id'],
                'time' => $myrow['content_create'],
                'uid' => $myrow['content_author'],
                'image' => 'images/logo_small.png',
            ];
        }

        return $items;
    }
}
