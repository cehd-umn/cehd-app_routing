<?php
namespace CEHD\App\KIN;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/App/App.php';
require_once __DIR__ . '/../../../cehd/app/traits/SlimRequest.php';

class KINApp extends \CEHD\App\App\BaseApp
{
    use \CEHD\App\SlimRequest;

    protected $_view;

    protected $_routes;

    // Overwrite the old constructor so we don't have all the old set up w/
    // instantiation
    // @todo use type hinting
    function __construct($container)
    {
        $this->_view = $container->get("view");
        $this->_routes = $container->get("routes");
    }

    // @todo use type hinting
    public function renderTemplateFromRoute($request, $response, $args)
    {
        $path = $this->getPathFromRequest($request);
        $route_info = $this->_routes->get($path);
        $this->_view->render($response, $route_info["template"], []);
        return $response;
    }

    // @todo: not sure if this will be needed
//    public function personExtraData($request, $response, $args)
//    {
//        $result = [];
//        $query_data = $request->getQueryParams();
//        $extra_content_file = __DIR__ . "/../../../cehd/config/olpd/people/extra_content.yml";
//        $extra_content = $this->loadYaml($extra_content_file);
//        $result = $extra_content;
//
//        if (array_key_exists("internetId", $query_data)) {
//            $internet_id = $query_data["internetId"];
//
//            if ($this->arrayContains($internet_id, $extra_content)) {
//                $result = $extra_content[$internet_id];
//            }
//        }
//        return $response->withJson($result);
//    }

    // @todo use type hinting
    public function personPage($request, $response, $args)
    {
        $internet_id = $args["internet_id"];
        $this->_view->render($response, "kin/people/person.html", ["internet_id" => $internet_id]);
        return $response;
    }

    // @todo use type hinting
    public function searchPage($request, $response, $args)
    {
        $config_file = __DIR__ . "/../../../cehd/config/kin/config.yml";
        $app_config = $this->loadYaml($config_file);
        $http_client = $this->bootstrapHTTPClient([]);
        $query_data = $request->getQueryParams();
        $base_url = "http://" . $_SERVER['SERVER_NAME'] . "/services/search/";
        $gsa_defaults = $app_config["search_sites"]["primary"];
        $start =  $this->arrayContains("start", $query_data) ? $query_data["start"] : 0;
        $site =   $this->arrayContains("site", $query_data) ? $query_data["site"] : $gsa_defaults["site"];
        $client = $this->arrayContains("client", $query_data) ? $query_data["client"] : $gsa_defaults["client"];
        $num =    $this->arrayContains("num", $query_data) ? $query_data["num"] : 10;
        $name =   $this->arrayContains("name", $query_data) ? $query_data["name"] : $gsa_defaults["name"];
        $filter = $this->arrayContains("filter", $query_data) ? $query_data["filter"] : "primary";
        $url = null;

        if ($this->arrayContains("url", $query_data)) {
            $url = $query_data["url"];
        }

        $search_query = $query_data["q"];

        $client_query_data = [
            'query' => [
                'client' => $client,
                'num' => $num,
                'q' => $search_query,
                'site' => $site,
                'start' => $start,
            ]
        ];

        if ($url) {
            $client_query_data["query"]["in_url"] = $url;
        }

        $client_response = $http_client->request("GET", $base_url, $client_query_data);

        $search_data = json_decode($client_response->getBody(), true);

        $template_data = [
            "client" => $client,
            "num" => $num,
            "site" => $site,
            "start" => $start,
            "in_url" => $url,
            "filter" => $filter,
            "query" => $search_query,
            "results" => $search_data["results"],
            "key_match" => $search_data["keyMatch"],
        ];

        $template = 'cehd/search/index.html';
        $this->_view->render($response, "kin/search/index.html", $template_data);
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
