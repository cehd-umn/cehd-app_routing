<?php
namespace CEHD\App\OLPD;

require_once __DIR__ . '/../../../vendor/autoload.php';

use \Peloton;

class OLPDApp extends Peloton
{
    protected $_searchApplianceSites = [
                "primary" => [
                    "name" => "Organizational Leadership, Policy and Development",
                    "url" => "www.cehd.umn.edu",
                ],
                "secondary" => [
                    "name" => "College of Education + Human Development",
                    "url" => "www.cehd.umn.edu",
                ],
                "tertiary" => [
                    "name" => "University of Minnesota",
                    "url" => "www.umn.edu",
                ]
            ];

    function __construct($file_paths)
    {
        parent::__construct($file_paths);

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
