<?php

namespace CEHD\App;
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../cehd/app/Config/Config.php";

class BaseClass
{
    /**
     * Handles Twig setup
     * @param boolean $debug - debug mode for Twig
     * @return object Twig_Environment
     */
    public function bootstrapTwig($debug=false)
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . "/../../templates");
        $twig = new \Twig_Environment($loader, array(
            "cache" => __DIR__ . "/../../cache",
            "auto_reload" => true, // this setting forces the templates to recompile if they are modified
            "debug" => $debug,
            "autoescape" => true,
        ));

        if ($debug)
        {
            $twig->addExtension(new \Twig_Extension_Debug());
        }
        return $twig;
    }

    /**
     * Handles Guzzle http client setup
     * @param array $params - Guzzle params
     * @return object Guzzle
     */
    public function bootstrapHTTPClient($params)
    {
        return new \GuzzleHttp\Client($params);
    }

    /**
     * Handles Config object setup
     * @param array $yaml_file_paths - an array of paths to yaml files
     * @return object Config
     */
    public function bootstrapConfig($yaml_file_paths)
    {
        $config = new \CEHD\App\Config\Config;
        return $config->load($yaml_file_paths);
    }

    /**
     * Convenience method for hyphenating phrases
     * @param string $str - a short phrase
     * @return string - a hyphenated version of the phrase
     */
    public function hyphenate($str, array $noStrip = [])
    {
        // http://www.mendoweb.be/blog/php-convert-string-to-hyphenated-string/
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        $str = str_replace(" ", "-", $str);
        $str = strtolower($str);

        return $str;
    }

    /**
     * Converts a file path to an array of its components
     * @param string $path - a path separated by "/"
     * @return array - the path components
     */
    public function pathToArray($path)
    {
        $path = ltrim($path, "/");
        $path = rtrim($path, "/");
        return explode("/", $path);
    }

    /**
     * Gets the last word in a path
     * @param string $path - a path separated by "/"
     * @return string - the last word in the path
     */
    public function getLastFromPath($path)
    {
        $path_array = $this->pathToArray($path);
        return array_pop($path_array);
    }

    /**
     * Strips ".html" off the end of a path
     * @param string $uri - a path separated by "/"
     * @return string - the last word in the path
     */
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

    /**
     * Parses a YAML file into an associative array
     * @param string $file_path - a valid file path
     * @return array - an associative array of the data in the YAML file
     */
    public function loadYaml($file_path)
    {
        $yaml = new \Symfony\Component\Yaml\Yaml;
        return $yaml::parse(file_get_contents($file_path));
    }

    /**
     * Tests whether something is iterable, i.e., it's OK to use it in a
     * foreach loop
     * @param mixed $obj - the thing to test
     * @return boolean - whether the thing is iterable
     */
    public function isIterable($obj)
    {
        return is_array($obj) || is_object($obj) || $obj instanceof \Traversable;
    }

    public function parseXMLErrors($errors)
    {
        $error_msgs = [];

        foreach($errors as $error)
        {
            $msg = str_repeat('-', $error->column) . "^\n";

            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                    $msg .= "Warning " . $error->code . ": ";
                    break;
                case LIBXML_ERR_ERROR:
                    $msg .= "Error " . $error->code . ": ";
                    break;
                case LIBXML_ERR_FATAL:
                    $msg .= "Fatal Error " . $error->code .": ";
                    break;
            }

            $msg .= trim($error->message) .
                "\n  Line: $error->line" .
                "\n  Column: $error->column";

            if ($error->file) {
                $msg .= "\n  File: $error->file";
            }
            
            array_push($error_msgs, $msg);
        }

        return $error_msgs;
    }
}
