<?php
namespace CEHD\App\OLPD;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/App/App.php';

class OLPDApp extends \CEHD\App\App\BaseApp
{
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

    function __construct()
    {
    }

    public function errorPageHandler($code=404, $router)
    {
        // @todo Create separate 404 template
        $html = $this->_renderTemplate('cehd/error/404.html', []);
        $router->response()->status($code);
        $router->response()->body($html);
    }
}
