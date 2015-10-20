<?php

namespace Xoops\Core\Locale\Punic;

/**
 * Overrides for Punic\Calendar.
 */
class Calendar extends \Punic\Calendar
{
    /**
     * Change to disable interpreteting 'yy' as forcing 2 digit year
     *
     * Instead of interpreting 2015 as '15' it will be '2015', while year 1 would be reported as '01'
     *
     * @param \DateTime $value
     * @param           $count
     * @param           $locale
     * @return string
     */
    protected static function decodeYear(\DateTime $value, $count, $locale)
    {
        switch ($count) {
            case 1:
                return strval(intval($value->format('Y')));
            case 2:
                //return $value->format('y');
            default:
                $s = $value->format('Y');
                if (!isset($s[$count])) {
                    $s = str_pad($s, $count, '0', STR_PAD_LEFT);
                }

                return $s;
        }
    }
}
