<?php
namespace CEHD\Helpers\Helper;

class Helper {
    public static function hyphenate($str, array $noStrip = [])
    {
        // http://www.mendoweb.be/blog/php-convert-string-to-hyphenated-string/
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        $str = str_replace(" ", "-", $str);
        $str = strtolower($str);

        return $str;
    }
}
