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
}
