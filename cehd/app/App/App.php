<?php
namespace CEHD\App\App;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Bootstrap.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Navigation.php';

use Klein;

/**
 * Custom App class
 */
class App extends \Klein\App
{
    protected $base_path;

    protected $yaml_file_paths;

    // public $base_config_path;

    public $router;

    public $config;

    public $bootstrap_helpers;

    public $nav_items;

    function __construct($yaml_file_paths)
    {
        $this->base_path = __DIR__ . '/../../';
        // $this->base_config_path = $this->base_path . 'cehd/config/app/config.yml';
        $this->router = new Klein\Klein(null, $this);
        $this->bootstrap_helpers = new \CEHD\Helpers\Bootstrap\Bootstrap;
        $this->yaml_file_paths = $yaml_file_paths;
        $this->config = $this->_setConfig();
    }

    protected function _setConfig()
    {
        return $this->bootstrap_helpers->bootstrap_config($this->yaml_file_paths["configs"]);
    }

    protected function _setNav()
    {
        $nav_helper = new CEHD\Helpers\Navigation\Navigation();
        $nav_helper->set_nav($this->yaml_file_paths["nav"]);
        $this->nav_items = $nav_helper->get_nav();
    }

    protected function _configureRouter()
    {

    }

    protected function _configureApp()
    {
        /**
        * Register Twig as the template engine for the app
        */
        $this->register('template', function() {
            // Set up nav from yaml file

            // // Set up configuration variables for Twig
            // $asset_base = $this->config['base_url'] . '/assets';
            // $nunjucks_view_base = $asset_base . '/dist/views';
            // $css_base = $asset_base . '/dist/css';
            // $js_base = $asset_base . '/dist/js';
            // $cehd_edpsych_nav_open = false;
            // $twig = $bootstrap_helper::bootstrap_twig($this->config['debug']);
            // $twig_config = [
            //     'asset_base' => $asset_base,
            //     'base_url' => $this->config['base_url'],
            //     'copyright_year' => date('Y'),
            //     'css_base' => $css_base,
            //     'dept_name' => $this->config['dept_name'],
            //     'js_base' => $js_base,
            //     'nunjucks_view_base' => $nunjucks_view_base,
            //     'nav_items' => $nav_helper->get_nav(),
            //     'site_config' => $this->config,
            // ];

            // // Check for main nav state
            // if (array_key_exists('cehd-edpsych-navOpen', $_COOKIE)) {
            //     $cehd_edpsych_nav_open = $_COOKIE['cehd-edpsych-navOpen'];
            // }

            // // Add global variables to Twig
            // $twig->addGlobal('config', $twig_config);
            // $twig->addGlobal('cehd_edpsych_nav_open', $cehd_edpsych_nav_open);
            // return $twig;
        });
    }
}
