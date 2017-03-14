<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 * @version 0.1.1
 *
 * The class to contain static helper methods related to bootstrapping the app
 *
 */
namespace CEHD\Helpers\Bootstrap;

const BASE_PATH = __DIR__ . '/../../../';

require_once BASE_PATH . 'vendor/autoload.php';
require_once BASE_PATH . 'cehd/app/Config/Config.php';

/**
 * The Bootstrap class
 *
 * This class has no constructor. The class can be assigned as a class
 * attribute and the methods here can be called directly.
 *
 * @example $this->bootstrap_helpers = new \CEHD\Helpers\Bootstrap\Bootstrap;
 *          $twig = $this->bootstrap_helpers->bootstrap_twig($debug=false);
 */
class Bootstrap
{
    /**
     * Handles Twig setup
     * @param boolean $debug - debug mode for Twig
     * @return object Twig_Environment
     */
    static function bootstrap_twig($debug=false)
    {
        $loader = new \Twig_Loader_Filesystem(BASE_PATH . 'templates');
        $twig = new \Twig_Environment($loader, array(
            'cache' => BASE_PATH . 'cache',
            'auto_reload' => true, // this setting forces the templates to recompile if they are modified
            'debug' => $debug,
            'autoescape' => true,
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
    static function bootstrap_http_client($params)
    {
        return new \GuzzleHttp\Client($params);
    }

    /**
     * Loads yaml data into an array
     * @deprecated Use Helper::loadYaml instead
     * @param string $file_path
     * @return array containing data in the yaml file
     */
    static function load_yaml($file_path)
    {
        $yaml = new \Symfony\Component\Yaml\Yaml;
        return $yaml::parse(file_get_contents($file_path));
    }

    /**
     * Handles Config object setup
     * @param array $yaml_file_paths - an array of paths to yaml files
     * @return object Config
     */
    static function bootstrap_config($yaml_file_paths)
    {
        $config = new \CEHD\App\Config\Config;
        return $config->load($yaml_file_paths);
    }
}
