<?php

declare(strict_types=1);

class Utils
{
    public static function seconds2human($seconds): string
    {
        $seconds = intval($seconds);
        $hours = intval($seconds / 3600);
        $minutes = intval(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        $result = '';
        if ($hours > 0) {
            $result .= "$hours:";
        }
        $result .= str_pad(strval($minutes), 2, '0', STR_PAD_LEFT);
        $result .= ':';
        $result .= str_pad(strval($seconds), 2, '0', STR_PAD_LEFT);
        return $result;
    }

    public static function currentSmax($current, $max): string
    {
        return "${current} / ${max}";
    }
}
