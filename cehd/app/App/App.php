<?php
namespace CEHD\App\App;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Bootstrap.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Navigation.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Helper.php';

use Klein;
use UMN\CEHD\Dict;

/**
 * Custom BaseApp class
 */
class BaseApp extends \Klein\App
{
    protected $base_path;

    protected $yaml_file_paths;

    protected $routes;

    public $router;

    public $config;

    public $bootstrap_helpers;

    public $nav_items;

    public $copyright_yr;

    function __construct($yaml_file_paths)
    {
        $this->base_path = __DIR__ . '/../../';
        $this->router = new Klein\Klein(null, $this);
        $this->bootstrap_helpers = new \CEHD\Helpers\Bootstrap\Bootstrap;
        $this->yaml_file_paths = $yaml_file_paths;
        $this->config = $this->_setConfig();
        $this->nav_items = $this->_setNav();
        $this->copyright_yr = date("Y");

        $this->register('template', function() {
            $twig = $this->bootstrap_helpers->bootstrap_twig($this->config['debug']);
            $twig->addGlobal('config', $this->config);
            $twig->addGlobal('nav_open', $this->config["nav_open"]);
            return $twig;
        });

        $this->register('http_client', function() {
            $http_client = $this->bootstrap_helpers->bootstrap_http_client([]);
            return $http_client;
        });

        $this->register('helpers', function() {
            $helpers = new \CEHD\Helpers\Helper\Helper;
            return $helpers;
        });

        $this->router->respond('GET', '*', function($request, $response, $service, $app) {
            $uri_components = explode("?", $request->uri());

            $uri = $uri_components[0];

            if (count($uri_components) > 1)
            {
                $query_str = $uri_components[1];
            }
            else
            {
                $query_str = "";
            }

            if (!strpos($uri, "services"))
            {
                // grab the last character in the uri to see if it's a slash
                $last = substr($uri, -1);

                // grab the last 4 characters to see if it's "html". Anything ending in
                // .html is likely to need a specific redirect
                $html = substr($uri, -4);

                if ($last !== "/" && $html !== "html")
                {
                    $with_slash = $uri . "/";
                    $redirect_uri = $uri . "/" . $query_str;
                    $response->redirect($redirect_uri, 301)->send();
                }
            }
        });

        $this->routes =  new Dict\Dict(
            $this->bootstrap_helpers->load_yaml(
                $this->yaml_file_paths["routes"])
        );

        foreach($this->routes as $route => $route_info)
        {
            $this->router->respond('GET', $route, $this->_loadStaticTemplate);
        }
    }

    protected function _setConfig()
    {
        // merge the contents of the yaml configs
        $config =  new Dict\Dict(
            $this->bootstrap_helpers->bootstrap_config(
                $this->yaml_file_paths["configs"]
            )
        );

        // build a few more in
        $config->asset_base = $this->_setSubBasePath($config, "base_url", "/assets");
        $config->nunjucks_view_base = $this->_setSubBasePath($config, "asset_base", "/dist/views");
        $config->css_base = $this->_setSubBasePath($config, "asset_base", "/dist/css");
        $config->js_base = $this->_setSubBasePath($config, "asset_base", "/dist/js");
        $config->nav_open = false;

        // Check for main nav state
        if (array_key_exists('cehd-navOpen', $_COOKIE)) {
            $config->nav_open = $_COOKIE['cehd-navOpen'];
        }
        return $config->toArray();
    }

    // $config_obj should be a Dict instance
    protected function _setSubBasePath($config_obj, $base_key, $new_sub_path)
    {
        return $config_obj->get($base_key) ? $config_obj->$base_key . $new_sub_path : $new_sub_path;
    }

    protected function _setNav()
    {
        $nav_helper = new \CEHD\Helpers\Navigation\Navigation();
        $nav_helper->set_nav($this->yaml_file_paths["nav"]);
        return $nav_helper->get_nav();
    }

    protected function _loadStaticTemplate(
        $request, $response, $service, $app)
    {
        $route_info = $this->routes->get($request->uri());
        if ($route_info)
        {
            echo $app->template->render(
                $route_info["template"],
                $route_info["template_data"]
            );
        };
    }
}
