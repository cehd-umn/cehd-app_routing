<?php
namespace CEHD\App\OLPD;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/App/App.php';
require_once __DIR__ . '/../../../cehd/app/traits/SlimRequest.php';

class OLPDApp extends \CEHD\App\App\BaseApp
{
    use \CEHD\App\SlimRequest;

    protected $_searchApplianceSites = [
                "primary" => [
                    "client" => "tc_education",
                    "name" => "Organizational Leadership, Policy and Development",
                    "site" => "tc_education_students",
                    "url" => "www.cehd.umn.edu/olpd/",
                ],
                "secondary" => [
                    "client" => "tc_education",
                    "name" => "College of Education + Human Development",
                    "site" => "tc_education_students",
                    "url" => "www.cehd.umn.edu",
                ],
                "tertiary" => [
                    "client" => "searchumn",
                    "name" => "University of Minnesota",
                    "site" => "default_collection",
                    "url" => "www.umn.edu",
                ]
            ];

    protected $_view;

    protected $_routes;

    // Overwrite the old constructor so we don't have all the old set up w/
    // instantiation
    function __construct($container)
    {
        $this->_view = $container->get("view");
        $this->_routes = $container->get("routes");
    }

    public function renderTemplateFromRoute($request, $response, $args)
    {
        $path = $this->getPathFromRequest($request);
        $route_info = $this->_routes->get($path);
        $this->_view->render($response, $route_info["template"], []);
        return $response;
    }

    // public function errorPageHandler($code=404, $router)
    // {
    //     // @todo Create separate 404 template
    //     $html = $this->_renderTemplate('cehd/error/404.html', []);
    //     $router->response()->status($code);
    //     $router->response()->body($html);
    // }
}
