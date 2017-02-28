<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 * @version 0.1.0
 *
 * The Base App for maintaining routes and templates for CEHD sites
 *
 * This is a base class that sets up a lot of functionality that is common
 * across sites. Do not add site-specific code here. Create a new class to
 * extend this class instead.
 *
 */
namespace CEHD\App\App;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Bootstrap.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Navigation.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Helper.php';
require_once __DIR__ . '/../../../cehd/app/Response/BaseResponse.php';

use Klein;
use UMN\CEHD\Dict;

/**
 * BaseApp class
 *
 * This class requires paths to config files formatted as YAML.
 *
 * Instantiate the app by passing in an array containing paths to said YAML
 * files.
 *
 * @example routes/cehd/static_routes.php
 */
class BaseApp
{
    /**
     * Class properties
     */

    /**
     * Array of file paths
     *
     * @type array
     */
    protected $file_paths;

    /**
     * Dict object containing routes
     *
     * @type Dict
     */
    protected $_routes;

    /**
     * Dict object containing redirects in lowercase format
     *
     * @type Dict
     */
    protected $_redirects;

    /**
     * Dict object containing redirects in camelCase for PascalCase format
     *
     * @type Dict
     */
    public $_redirects_camelcase;

    /**
     * Route handler
     *
     * @type Klein
     */
    public $router;

    /**
     * App config info
     *
     * @type Dict
     */
    public $config;

    /**
     * Generic helper methods
     *
     * @type Bootstrap
     */
    public $bootstrap_helpers;

    /**
     * Navigation items
     *
     * @type array
     */
    public $nav_items;

    /**
     * Current copyright year
     *
     * @type string
     */
    public $copyright_yr;

    /**
     * Minified styles to inject into template head for page speed
     *
     * @type string
     */
    public $inline_styles;

    /**
     * Template engine
     *
     * @type Twig
     */
    public $template;

    /**
     * More generic helpers
     *
     * @todo Merge Bootstrap and Helper class
     * @type Helper
     */
    public $helpers;

    /**
     * Response class
     *
     * @type Klein\Response
     */
    public $response;

    /**
     * Methods
     */

    /**
     * Constructor
     *
     * Create a new BaseApp instance configured by data in a series of YAML
     * files
     *
     * @param mixed[] $file_paths - array of file paths
     */
    function __construct($file_paths)
    {
        $this->router = new Klein\Klein(null, $this);
        $this->bootstrap_helpers = new \CEHD\Helpers\Bootstrap\Bootstrap;
        $this->file_paths = $file_paths;
        $this->config = $this->_setConfig();
        $this->nav_items = $this->_setNav();
        $this->copyright_yr = date("Y");
        $this->inline_styles = array_key_exists("inline_styles", $this->file_paths) ? file_get_contents($this->file_paths["inline_styles"]) : null;

        $twig = $this->bootstrap_helpers->bootstrap_twig($this->config['debug']);
        $twig->addGlobal('config', $this->config);
        $twig->addGlobal('nav_items', $this->nav_items);
        $twig->addGlobal('inline_styles', $this->inline_styles);
        $this->template = $twig;

        $this->response = new \CEHD\App\Response\BaseResponse();
        $this->helpers = new \CEHD\Helpers\Helper\Helper;
        $this->_routes =  new Dict\Dict(
            $this->bootstrap_helpers->load_yaml(
                $this->file_paths["routes"])
        );

        // load the redirects from the yaml file provided or set and empty Dict object
        $this->_redirects = array_key_exists("redirects", $this->file_paths) ? $this->_setFromYAML($this->file_paths["redirects"]) : $this->_loadDict([]);
        $this->_redirects_camelcase = array_key_exists("redirects_camelcase", $this->file_paths) ? $this->_setFromYAML($this->file_paths["redirects_camelcase"]) : $this->_loadDict([]);

        // Adds a trailing slash if it's missing
        // preserves query params if they exist
        // runs on *every* request
        $this->router->respond('GET', '*', function($request, $response, $service, $app) {

            // split the uri into the url and the query params
            $uri_components = explode("?", $request->uri());


            // the first element in the new array will be the uri
            $uri = $uri_components[0];

            if (count($uri_components) > 1)
            {
                // preserve the query string
                $query_str = "?" . $uri_components[1];
            }
            else
            {
                $query_str = "";
            }

            // Don't do this for services
            if (!strpos($uri, "services"))
            {
                // grab the last character in the uri to see if it's a slash
                $last = substr($uri, -1);

                // grab the last 4 characters to see if it's "html". Anything ending in
                // .html is likely to need a specific redirect
                $html = substr($uri, -4);

                if ($last !== "/" && $html !== "html")
                {
                    // put it all back together and redirect
                    $redirect_uri = $uri . "/" . $query_str;
                    $response->redirect($redirect_uri, 301)->send();
                }
            }
        });

        // Loops through the redirects and redirects on a match
        foreach($this->_redirects as $old_route => $new_route)
        {
            $this->router->respond("GET", $old_route, function(
                $request, $response, $service, $app)
            {
                // Most redirects will match a key that is the lowercase
                // version of the URI.
                $redirect_route = $this->_redirects->get(
                    strtolower($request->uri()));

                if ($redirect_route)
                {
                    $response->redirect($redirect_route, 301)->send();
                }
            });
        }

        // Some redirects need to be checked using camelCase or PascalCase
        // routes because their lowercase versions match existing routes
        foreach($this->_redirects_camelcase as $old_route => $new_route)
        {
            $this->router->respond("GET", $old_route, function(
                $request, $response, $service, $app)
            {
                $redirect_route = $this->_redirects_camelcase->get(
                    $request->uri());

                if ($redirect_route)
                {
                    $response->redirect($redirect_route, 301)->send();
                }
            });
        }

        // Finally, we get to the actual routes
        foreach($this->_routes as $route => $route_info)
        {
            $this->router->respond('GET', $route, function(
                $request, $response, $service, $app)
            {
                echo $this->_loadStaticTemplate(
                    $request, $response, $service, $app);
            });
        }
    }

    /**
     * Loads array data into a Dict instance
     *
     * @param array $data
     * @return Dict
     */
    protected function _loadDict($data)
    {
        return new Dict\Dict($data);
    }

    /**
     * Returns data from YAML files as a Dict or optionally as an array
     *
     * @param string $file_path - a valid path to a YAML file
     * @param boolean $as_dict - whether to return a dict (default) or array
     * @return Dict or array
     */
    protected function _setFromYAML($file_path, $as_dict=true)
    {
        try
        {
            $data_array = $this->bootstrap_helpers->load_yaml($file_path);
            if ($as_dict)
            {
                return $this->_loadDict($data_array);
            }

            return $data_array;
        }
        catch (Exception $e)
        {
            echo "Problem loading YAML file: " .
                "File path: " . $file_path . "\n" .
                $e->getMessage() . "\n";
        }

        return [];
    }

    /**
     * Merges several configs into one and then builds various config
     * properties
     *
     * @todo Creating a defined Config class would be more performant
     *
     * @return array
     */
    protected function _setConfig()
    {
        // merge the contents of the yaml configs
        $config =  new Dict\Dict(
            $this->bootstrap_helpers->bootstrap_config(
                $this->file_paths["configs"]
            )
        );

        // build a few more in
        $config->asset_base = $this->_setSubBasePath(
            $config, "base_url", "/assets");
        $config->nunjucks_view_base = $this->_setSubBasePath(
            $config, "asset_base", "/dist/views");
        $config->css_base = $this->_setSubBasePath(
            $config, "asset_base", "/dist/css");
        $config->js_base = $this->_setSubBasePath(
            $config, "asset_base", "/dist/js");
        $config->nav_open = false;

        // Check for main nav state
        if (array_key_exists('cehd-navOpen', $_COOKIE)) {
            $config->nav_open = $_COOKIE['cehd-navOpen'];
        }
        // convert the config back to an array
        return $config->toArray();
    }

    /**
     * A convenience method for setting up various paths in the config
     *
     * @param Dict $config_obj
     * @param string $base_key - the base path to which we want to append
     * @param string $new_sub_path - the new path to append
     * @return string - of combined base path and new sub path or new sub path
     *         of base path doesn't exist
     */
    protected function _setSubBasePath($config_obj, $base_key, $new_sub_path)
    {
        return $config_obj->get($base_key) ? $config_obj->$base_key . $new_sub_path : $new_sub_path;
    }

    /**
     * Sets navigation items from YAML
     *
     * @return array
     */
    protected function _setNav()
    {
        $nav_helper = new \CEHD\Helpers\Navigation\Navigation();
        $nav_helper->set_nav($this->file_paths["nav"]);
        return $nav_helper->get_nav();
    }

    /**
     * Callback method for loading templates based on routes. If the request
     * URI matches a key in the routes property, the corresponding template
     * is rendered. Klein automatically populates the params for all callbacks.
     *
     * @param Klein $request object
     * @param Klein $response object
     * @param Klein $service object
     * @param Klein $app object - In this case, an instance of this app
     * @return string - rendered template if the URI matches a route key or
     *         null if not
     */
    protected function _loadStaticTemplate(
        $request, $response, $service, $app)
    {
        $route_info = $this->_routes->get($request->uri());
        if ($route_info)
        {
            return $app->template->render(
                $route_info["template"],
                $route_info["template_data"]
            );
        };
    }
}
