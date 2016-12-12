<?php
namespace CEHD\Helpers\Bootstrap;

const BASE_PATH = __DIR__ . '/../../../';

require_once BASE_PATH . 'vendor/autoload.php';
require_once BASE_PATH . 'cehd/app/Config/Config.php';

class Bootstrap
{
    static function bootstrap_twig($debug=false)
    {
        $loader = new \Twig_Loader_Filesystem(BASE_PATH . 'templates');
        $twig = new \Twig_Environment($loader, array(
            'cache' => BASE_PATH . 'cache', // Look here if vars don't show up
            'debug' => $debug,
            'autoescape' => true,
        ));

        if ($debug)
        {
            $twig->addExtension(new \Twig_Extension_Debug());
        }
        return $twig;
    }

    static function bootstrap_http_client($params)
    {
        return new \GuzzleHttp\Client($params);
    }

    static function load_yaml($file_path)
    {
        $yaml = new \Symfony\Component\Yaml\Yaml;
        return $yaml::parse(file_get_contents($file_path));
    }

    static function bootstrap_config($yaml_file_paths)
    {
        $config = new \CEHD\App\Config\Config;
        return $config->load($yaml_file_paths);
    }
}
