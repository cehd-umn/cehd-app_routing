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

    public function pathToArray($path)
    {
        $path = ltrim($path, "/");
        $path = rtrim($path, "/");
        return explode("/", $path);
    }

    public function getLastFromPath($path)
    {
        $path_array = $this->pathToArray($path);
        return array_pop($path_array);
    }

    public function stripDotHtml($uri, $lowercase=false)
    {
        if (strrpos($uri, ".html") > 0)
        {
            // strip .html (last 5 characters)
            $stripped = substr($uri, 0, -5);

            if ($lowercase)
            {
                $stripped = strtolower($stripped);
            }
            return $stripped;
        }

        return $uri;
    }
}
