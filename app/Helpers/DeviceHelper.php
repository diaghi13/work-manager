<?php

namespace App\Helpers;

class DeviceHelper
{
    public static function isMobile()
    {
        return is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"));
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
}
