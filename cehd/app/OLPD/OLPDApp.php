<?php
namespace CEHD\App\OLPD;

require_once __DIR__ . '/../../../vendor/autoload.php';

class OLPDApp extends \Peloton\Peloton
{
    protected $_searchApplianceSites = [
                "primary" => [
                    "client" => "tc_education",
                    "name" => "Organizational Leadership, Policy and Development",
                    "site" => "tc_education_students",
                    "url" => "www.cehd.umn.edu",
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

    public function __construct($file_paths, $templates_dir)
    {
        parent::__construct($file_paths, $templates_dir);

        $this->router->onHttpError([$this, 'errorPageHandler']);
    }

    public function errorPageHandler($code=404, $router)
    {
        // @todo Create separate 404 template
        $html = $this->_renderTemplate('cehd/error/404.html', []);
        $router->response()->status($code);
        $router->response()->body($html);
    }
}
